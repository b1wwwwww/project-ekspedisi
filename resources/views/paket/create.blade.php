<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Input Paket
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">
                    Form Penerimaan Paket
                </h3>

            <form action="{{ route('paket.store') }}" method="POST">
                @csrf
                <div class="mt-6">
                    <h4 class="text-md font-semibold mb-4">
                        A. Data Pengirim
                    </h4>

                    <div class="mb-4">
                        <label for="telepon_pengirim" class="block mb-2">
                            Nomor Telepon Pengirim
                        </label>

                        <input
                            type="text"
                            id="telepon_pengirim"
                            name="telepon_pengirim"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan nomor telepon pengirim"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="nama_pengirim" class="block mb-2">
                            Nama Pengirim
                        </label>

                        <input
                            type="text"
                            id="nama_pengirim"
                            name="nama_pengirim"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan nama pengirim"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="alamat_pengirim" class="block mb-2">
                            Alamat Pengirim
                        </label>

                        <textarea
                            id="alamat_pengirim"
                            name="alamat_pengirim"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan alamat pengirim"
                        ></textarea>
                    </div>
                </div>


                <div class="mt-8 border-t pt-6">
                    <h4 class="text-md font-semibold mb-4">
                        B. Data Penerima
                    </h4>

                    <div class="mb-4">
                        <label for="penerima_nama" class="block mb-2">
                            Nama Penerima
                        </label>

                        <input
                            type="text"
                            id="penerima_nama"
                            name="penerima_nama"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan nama penerima"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="penerima_telepon" class="block mb-2">
                            Nomor Telepon Penerima
                        </label>

                        <input
                            type="text"
                            id="penerima_telepon"
                            name="penerima_telepon"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan nomor telepon penerima"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="penerima_alamat" class="block mb-2">
                            Alamat Lengkap Penerima
                        </label>

                        <textarea
                            id="penerima_alamat"
                            name="penerima_alamat"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan alamat lengkap penerima"
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="kota_tujuan" class="block mb-2">
                            Kota Tujuan
                        </label>

                        <input
                            type="text"
                            id="kota_tujuan"
                            name="kota_tujuan"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan kota tujuan"
                        >
                    </div>
                </div>


                <div class="mt-8 border-t pt-6">
                    <h4 class="text-md font-semibold mb-4">
                        C. Informasi Paket
                    </h4>

                    <div class="mb-4">
                        <label for="berat_kg" class="block mb-2">
                            Berat Paket (kg)
                        </label>

                        <input
                            type="number"
                            id="berat_kg"
                            name="berat_kg"
                            step="0.01"
                            min="0.01"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Contoh: 2.5"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="layanan" class="block mb-2">
                            Jenis Layanan
                        </label>

                        <select
                            id="layanan"
                            name="layanan"
                            class="border-gray-300 rounded-md w-full"
                        >
                            <option value="">-- Pilih Layanan --</option>
                            <option value="Reguler">Reguler</option>
                            <option value="Express">Express</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="nilai_cod" class="block mb-2">
                            Nilai COD (Opsional)
                        </label>

                        <input
                            type="number"
                            id="nilai_cod"
                            name="nilai_cod"
                            min="0"
                            step="1000"
                            value="0"
                            class="border-gray-300 rounded-md w-full"
                            placeholder="Masukkan nilai COD"
                        >
                    </div>
                </div>


                <div class="mt-8 border-t pt-6">
                    <h4 class="text-md font-semibold mb-4">
                        D. Ringkasan Tarif
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-2">Zona Tujuan</label>
                            <input
                                type="text"
                                id="ringkasan_zona"
                                value="Menunggu perhitungan sistem"
                                class="border-gray-300 rounded-md w-full bg-gray-100"
                                disabled
                            >
                        </div>

                        <div>
                            <label class="block mb-2">Berat Tagih</label>
                            <input
                                type="text"
                                id="ringkasan_berat"
                                value="Menunggu perhitungan sistem"
                                class="border-gray-300 rounded-md w-full bg-gray-100"
                                disabled
                            >
                        </div>

                        <div>
                            <label class="block mb-2">Tarif per Kg</label>
                            <input
                                type="text"
                                id="ringkasan_tarif"
                                value="Menunggu perhitungan sistem"
                                class="border-gray-300 rounded-md w-full bg-gray-100"
                                disabled
                            >
                        </div>

                        <div>
                            <label class="block mb-2">Ongkos Kirim</label>
                            <input
                                type="text"
                                id="ringkasan_ongkir"
                                value="Menunggu perhitungan sistem"
                                class="border-gray-300 rounded-md w-full bg-gray-100"
                                disabled
                            >
                        </div>

                        <div>
                            <label class="block mb-2">Biaya COD</label>
                            <input
                                type="text"
                                id="ringkasan_cod"
                                value="Menunggu perhitungan sistem"
                                class="border-gray-300 rounded-md w-full bg-gray-100"
                                disabled
                            >
                        </div>

                    </div>

                        <p id="pesan_perhitungan" class="mt-4 text-sm text-gray-600">
                            Lengkapi kota tujuan, berat, dan layanan untuk melihat estimasi tarif.
                        </p>

                        <div class="mt-8 border-t pt-6 flex justify-end gap-3">


                        <a
                            href="{{ route('paket.index') }}"
                            class="bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="bg-blue-600 text-gray-800 px-4 py-2 rounded-md hover:bg-blue-700"
                        >
                            Simpan Paket
                        </button>
                    </div>

                    <div id="pilihan_zona_manual" class="mt-4 hidden">
                        <label for="zona_manual" class="block mb-2 font-semibold">
                            Pilih Zona Tujuan Secara Manual
                        </label>

                        <select
                            id="zona_manual"
                            name="zona_manual"
                            class="border-gray-300 rounded-md w-full"
                        >
                            <option value="">-- Pilih Zona --</option>

                            @foreach ($zonaTarif as $zona)
                                <option value="{{ $zona->id }}">
                                    {{ $zona->nama_zona }}
                                    - Rp{{ number_format($zona->harga_per_kg, 0, ',', '.') }}/kg
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-yellow-700 mt-2">
                            Zona ditentukan secara manual karena API tidak dapat menentukan zona tujuan.
                        </p>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>



    {{-- hubungan endpoint javascript --}}

    <script>
        const kotaTujuan = document.getElementById('kota_tujuan');
        const beratKg = document.getElementById('berat_kg');
        const layanan = document.getElementById('layanan');
        const nilaiCod = document.getElementById('nilai_cod');

        const ringkasanZona = document.getElementById('ringkasan_zona');
        const ringkasanBerat = document.getElementById('ringkasan_berat');
        const ringkasanTarif = document.getElementById('ringkasan_tarif');
        const ringkasanOngkir = document.getElementById('ringkasan_ongkir');
        const ringkasanCod = document.getElementById('ringkasan_cod');
        const pesanPerhitungan = document.getElementById('pesan_perhitungan');
        const pilihanZonaManual = document.getElementById('pilihan_zona_manual');
        const zonaManual = document.getElementById('zona_manual');

        function formatRupiah(angka) {
            return 'Rp' + Number(angka).toLocaleString('id-ID');
        }

        async function hitungTarif() {
            const kota = kotaTujuan.value.trim();
            const berat = beratKg.value;
            const jenisLayanan = layanan.value;
            const cod = nilaiCod.value || 0;
            const zonaDipilih = zonaManual.value;

            if (!kota || !berat || Number(berat) <= 0 || !jenisLayanan) {
                pesanPerhitungan.textContent =
                    'Lengkapi kota tujuan, berat, dan layanan untuk melihat estimasi tarif.';
                return;
            }

            pesanPerhitungan.textContent = 'Sedang menghitung tarif...';

            try {
                const parameter = new URLSearchParams({
                    kota_tujuan: kota,
                    berat_kg: berat,
                    layanan: jenisLayanan,
                    nilai_cod: cod,
                    zona_manual: zonaDipilih
                });

                const response = await fetch(
                    `{{ route('paket.hitung-tarif') }}?${parameter}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                            // Laravel mengembalikan respons validasi dalam format JSON.sbS
                        }
                    }
                );

                const hasil = await response.json();

                if (!response.ok) {
                    if (hasil.perlu_zona_manual) {
                        pilihanZonaManual.classList.remove('hidden');

                        pesanPerhitungan.textContent = hasil.message;
                        return;
                    }

                    throw new Error(hasil.message || 'Gagal menghitung tarif.');
                }

                ringkasanZona.value = hasil.zona;
                ringkasanBerat.value = hasil.berat_tagih + ' kg';
                ringkasanTarif.value = formatRupiah(hasil.tarif_per_kg);
                ringkasanOngkir.value = formatRupiah(hasil.ongkir);
                ringkasanCod.value = formatRupiah(hasil.biaya_cod);

                pesanPerhitungan.textContent = zonaDipilih
                    ? 'Tarif dihitung menggunakan zona manual.'
                    : 'Perhitungan tarif berhasil.';

            } catch (error) {
                pesanPerhitungan.textContent = error.message;
            }
        }

        // Event listener dipasang satu kali di luar fungsi.
        kotaTujuan.addEventListener('change', () => {
            zonaManual.value = '';
            pilihanZonaManual.classList.add('hidden');

            hitungTarif();
        });

        beratKg.addEventListener('input', hitungTarif);
        layanan.addEventListener('change', hitungTarif);
        nilaiCod.addEventListener('input', hitungTarif);
        zonaManual.addEventListener('change', hitungTarif);
    </script>
</x-app-layout>