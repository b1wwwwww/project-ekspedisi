// Mengambil elemen checkbox paket.
const checkboxPaket = document.querySelectorAll('.checkbox-paket');

// Mengambil elemen radio kurir.
const radioKurir = document.querySelectorAll('.radio-kurir');

// Mengambil elemen informasi penugasan.
const jumlahPaketTerpilih = document.getElementById('penugasan-selected-count');
const namaKurirTerpilih = document.getElementById('penugasan-kurir-terpilih');
const sisaKapasitasKurir = document.getElementById('penugasan-sisa-kapasitas');
const tombolTugaskan = document.getElementById('btn-tugaskan-kurir');

// Menghitung jumlah paket yang dipilih.
function hitungPaketTerpilih() {
    return document.querySelectorAll('.checkbox-paket:checked').length;
}

// Memperbarui informasi penugasan.
function perbaruiPenugasan() {

    // Mengambil ID kurir dari paket yang sedang dipilih.
    const kurirSebelumnya = Array.from(
        document.querySelectorAll('.checkbox-paket:checked')
    )
        .map((checkbox) => checkbox.dataset.kurirSebelumnya)
        .filter((id) => id !== '');

    // Menonaktifkan kurir yang pernah menangani paket terpilih.
    radioKurir.forEach((radio) => {
        const kapasitasPenuh = Number(radio.dataset.sisa) <= 0;

        const pernahMenangani = kurirSebelumnya.includes(radio.value);

        radio.disabled = kapasitasPenuh || pernahMenangani;

        // Batalkan pilihan jika kurir yang dipilih menjadi tidak valid.
        if (radio.disabled && radio.checked) {
            radio.checked = false;
        }
    });

    const jumlahPaket = hitungPaketTerpilih();
    const kurirDipilih = document.querySelector('.radio-kurir:checked');

    // Memperbarui jumlah paket.
    jumlahPaketTerpilih.textContent = `${jumlahPaket} paket`;

    // Jika belum ada kurir yang dipilih.
    if (!kurirDipilih) {
        namaKurirTerpilih.textContent = 'Belum ada';
        sisaKapasitasKurir.textContent = '-';
        tombolTugaskan.disabled = true;
        tombolTugaskan.classList.add('opacity-50', 'cursor-not-allowed');

        return;
    }

    // Mengambil informasi kurir.
    const nama = kurirDipilih.dataset.nama;
    const sisaKapasitas = Number(kurirDipilih.dataset.sisa);

    namaKurirTerpilih.textContent = nama;
    sisaKapasitasKurir.textContent = `${sisaKapasitas} paket`;

    // Memeriksa apakah jumlah paket sesuai kapasitas.
    const kapasitasMencukupi =
        jumlahPaket > 0 && jumlahPaket <= sisaKapasitas;

    tombolTugaskan.disabled = !kapasitasMencukupi;

    tombolTugaskan.classList.toggle('opacity-50', !kapasitasMencukupi);
    tombolTugaskan.classList.toggle('cursor-not-allowed', !kapasitasMencukupi);
}

// Event ketika checkbox paket berubah.
checkboxPaket.forEach((checkbox) => {
    checkbox.addEventListener('change', perbaruiPenugasan);
});

// Event ketika radio kurir berubah.
radioKurir.forEach((radio) => {
    radio.addEventListener('change', perbaruiPenugasan);
});

// Menjalankan fungsi saat halaman pertama kali dibuka.
perbaruiPenugasan();