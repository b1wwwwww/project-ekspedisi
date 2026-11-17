
        // JAVASCRIPT PERHITUNGAN TARIF


        // Mengambil elemen HTML.
        const formInputPaket = document.getElementById('form-input-paket');

        const kotaTujuan = document.getElementById('kota_tujuan');
        const beratKg = document.getElementById('berat_kg');
        const layanan = document.getElementById('layanan');
        const nilaiCod = document.getElementById('nilai_cod');
        const zonaManual = document.getElementById('zona_manual');

        const ringkasanZona = document.getElementById('ringkasan_zona');
        const ringkasanBerat = document.getElementById('ringkasan_berat');
        const ringkasanTarif = document.getElementById('ringkasan_tarif');
        const ringkasanOngkir = document.getElementById('ringkasan_ongkir');
        const ringkasanCod = document.getElementById('ringkasan_cod');
        const ringkasanTotal = document.getElementById('ringkasan_total');

        const beratBulatLabel = document.getElementById('berat-bulat-label');
        const pesanPerhitungan = document.getElementById('pesan_perhitungan');

        const pilihanZonaManual = document.getElementById('pilihan_zona_manual');
        const statusZonaBadge = document.getElementById('status-zona-badge');
        const zonaHelperText = document.getElementById('zona-helper-text');

        const konfigurasi = document.getElementById('konfigurasi-paket');
        const urlHitungTarif = konfigurasi.dataset.urlHitungTarif;

        // Mengontrol permintaan API agar tidak saling bertabrakan.
        let permintaanAktif = null;
        let nomorPermintaan = 0;

        // Mengubah angka menjadi format Rupiah.
        function formatRupiah(angka) {
            return 'Rp' + Number(angka).toLocaleString('id-ID');
        }


        // Mengosongkan ringkasan tarif.
        function resetRingkasan() {
            ringkasanZona.textContent = '—';
            ringkasanBerat.textContent = '—';
            ringkasanTarif.textContent = '—';
            ringkasanOngkir.textContent = '—';
            ringkasanCod.textContent = '—';
            ringkasanTotal.textContent = '—';
        }


        // Mengatur badge ketika belum ada perhitungan.
        function setBadgeMenunggu() {

            statusZonaBadge.textContent = 'Menunggu Perhitungan';

            statusZonaBadge.className =
                'rounded px-2.5 py-1 text-[12px] font-semibold bg-slate-100 text-slate-500';

            zonaHelperText.textContent =
                'Zona dideteksi otomatis lewat API Pusat Wilayah berdasarkan kota tujuan.';
        }


        // Mengatur badge ketika zona berhasil dideteksi API.
        function setBadgeOtomatis() {

            statusZonaBadge.textContent = 'Otomatis (API)';

            statusZonaBadge.className =
                'rounded px-2.5 py-1 text-[12px] font-semibold bg-emerald-100 text-emerald-800';

            zonaHelperText.textContent =
                'Zona dideteksi otomatis lewat API Pusat Wilayah berdasarkan kota tujuan.';
        }


        // Mengatur badge ketika Admin memilih zona manual.
        function setBadgeManual() {

            statusZonaBadge.textContent = 'Manual (Perlu Verifikasi)';

            statusZonaBadge.className =
                'rounded px-2.5 py-1 text-[12px] font-semibold bg-amber-100 text-amber-800';

            zonaHelperText.textContent =
                'Zona dipilih secara manual oleh Admin karena API tidak dapat menentukan zona.';
        }


        // Fungsi utama untuk menghitung tarif.
        async function hitungTarif() {

            // Membuat nomor permintaan baru.
            const nomorSaatIni = ++nomorPermintaan;

            // Membatalkan permintaan sebelumnya jika masih berjalan.
            if (permintaanAktif) {
                permintaanAktif.abort();
            }

            // Mengambil nilai input.
            const kota = kotaTujuan.value.trim();
            const berat = beratKg.value;
            const jenisLayanan = layanan.value;
            const cod = nilaiCod.value || 0;
            const zonaDipilih = zonaManual ? zonaManual.value : '';


            // Menghitung berat tagih.
            if (berat && Number(berat) > 0) {
                beratBulatLabel.textContent =
                    Math.max(1, Math.ceil(Number(berat))) + ' kg';
            } else {
                beratBulatLabel.textContent = '—';
            }


            // Memeriksa kelengkapan input.
            if (!kota || !berat || Number(berat) <= 0 || !jenisLayanan) {
                resetRingkasan();
                if (zonaDipilih) {
                    setBadgeManual();
                } else {
                    setBadgeMenunggu();
                }

                pesanPerhitungan.textContent =
                    'Lengkapi kota tujuan, berat, dan layanan untuk melihat estimasi tarif.';

                return;
            }


            // Mengosongkan ringkasan sebelum menghitung ulang.
            resetRingkasan();

            pesanPerhitungan.textContent = 'Sedang menghitung tarif...';

            // Membuat controller untuk membatalkan request.
            const controller = new AbortController();

            permintaanAktif = controller;


            try {

                // Menyiapkan parameter yang dikirim ke Laravel.
                const parameter = new URLSearchParams({
                    kota_tujuan: kota,
                    berat_kg: berat,
                    layanan: jenisLayanan,
                    nilai_cod: cod,
                    zona_manual: zonaDipilih
                });


                // Mengirim permintaan ke endpoint Laravel.
                const response = await fetch(
                    `${urlHitungTarif}?${parameter}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        },
                        signal: controller.signal
                    }
                );


                // Mengubah respons menjadi JSON.
                const hasil = await response.json();


                // Mengabaikan respons yang sudah tidak terbaru.
                if (nomorSaatIni !== nomorPermintaan) {
                    return;
                }


                // Menangani ketika backend meminta zona manual.
                if (!response.ok) {

                    if (hasil.perlu_zona_manual) {
                        pilihanZonaManual.classList.remove('hidden');
                        resetRingkasan();
                        setBadgeManual();
                        pesanPerhitungan.textContent = hasil.message;

                        return;
                    }

                    throw new Error(
                        hasil.message || 'Gagal menghitung tarif.'
                    );
                }


                // Menampilkan hasil perhitungan.
                ringkasanZona.textContent = hasil.zona;

                ringkasanBerat.textContent =
                    berat + ' kg → ' + hasil.berat_tagih + ' kg';

                ringkasanTarif.textContent =
                    formatRupiah(hasil.tarif_per_kg);

                ringkasanOngkir.textContent =
                    formatRupiah(hasil.ongkir);

                ringkasanCod.textContent =
                    formatRupiah(hasil.biaya_cod);


                // Total pembayaran di loket.
                const totalBayar =
                    Number(hasil.ongkir) + Number(hasil.biaya_cod);

                ringkasanTotal.textContent =
                    formatRupiah(totalBayar);


                // Mengatur status zona.
                if (zonaDipilih) {

                    setBadgeManual();
                    pilihanZonaManual.classList.remove('hidden');

                } else {

                    setBadgeOtomatis();
                    pilihanZonaManual.classList.add('hidden');

                }

                pesanPerhitungan.textContent = zonaDipilih
                    ? 'Tarif dihitung menggunakan zona manual.'
                    : 'Perhitungan tarif berhasil.';


            } catch (error) {

                // Abaikan jika request memang dibatalkan.
                if (error.name === 'AbortError') {
                    return;
                }

                // Abaikan respons yang bukan permintaan terbaru.
                if (nomorSaatIni !== nomorPermintaan) {
                    return;
                }

                resetRingkasan();

                pesanPerhitungan.textContent =
                    'Terjadi kesalahan saat menghitung tarif. Silakan coba kembali.';

                console.error(
                    'Kesalahan perhitungan tarif:',
                    error
                );

            } finally {

                // Membersihkan controller jika request ini masih aktif.
                if (nomorSaatIni === nomorPermintaan) {
                    permintaanAktif = null;
                }
            }
        }


        // =================================================
        // EVENT LISTENER
        // =================================================


        // Ketika kota tujuan berubah.
        kotaTujuan.addEventListener('change', () => {

            // Zona manual sebelumnya tidak berlaku lagi.
            if (zonaManual) {
                zonaManual.value = '';
            }

            pilihanZonaManual.classList.add('hidden');
            setBadgeMenunggu();
            hitungTarif();
        });


        // Menghitung ulang ketika berat berubah.
        beratKg.addEventListener('input', hitungTarif);


        // Menghitung ulang ketika layanan berubah.
        layanan.addEventListener('change', hitungTarif);


        // Menghitung ulang ketika nilai COD berubah.
        nilaiCod.addEventListener('input', hitungTarif);


        // Menghitung ulang ketika zona manual dipilih.
        if (zonaManual) {
            zonaManual.addEventListener('change', hitungTarif);
        }

        // MENCEGAH PENGIRIMAN FORM BERULANG

        formInputPaket.addEventListener('submit', function () {
            const tombolSimpan =
                formInputPaket.querySelector('button[type="submit"]');
                tombolSimpan.disabled = true;
                tombolSimpan.textContent = 'Menyimpan Paket...';
        });

        // MEMULIHKAN PERHITUNGAN SETELAH VALIDASI GAGAL

        document.addEventListener('DOMContentLoaded', () => {
            if (kotaTujuan.value && beratKg.value && layanan.value) {
                hitungTarif();
            }
        });
