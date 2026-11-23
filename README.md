# Priangan Kilat Express - Sistem Ekspedisi

## 1. Ringkasan
Priangan Kilat Express (PKE)

Sistem Website + Apk ini digunakan oleh Admin Loket, Kurir Lapangan, dan Manajer Eksekutif, dengan akses web tanpa login bagi pelanggan untuk pelacakan paket. Aplikasi ini memecahkan kendala klien terkait ketidakakuratan perhitungan tarif, penugasan kurir yang sering kelebihan beban (overload), risiko setoran COD ganda, serta lambatnya pemantauan status paket. Alur kerja yang diterapkan dimulai dari penginputan data paket dan penerbitan resi otomatis di loket, verifikasi transit, penugasan paket ke kurir aktif dengan batas maksimal 20 paket, penugasan pengantaran kepada kurir dengan aturan maksimal jika paket dua kali gagal kirim maka status nya wajib menjadi retur, fitur setoran COD sekali catat, hingga rekap laporan pendapatan dan pengiriman untuk mempermudah pengambilan keputusan dan monitoring data oleh manager.

## 2. Teknologi

**Server & Web (Admin, Manajer, Cek Resi Publik):**
- Laravel 13 (PHP 8.5)
- Blade + Tailwind CSS
- MySQL
- Laravel Sanctum (autentikasi token untuk REST API)
- Integrasi eksternal: Pusat Wilayah API (lewat `App\Services\PusatWilayahService`)

**APK (Kurir):**
- Ionic + Capacitor + Angular (TypeScript)
- Mengonsumsi REST API lewat token Sanctum

**Alasan pemilihan stack:**
Laravel dipilih karena ini framework yang paling saya kuasai dan di dalami dari pembelajaran
di sekolah, sehingga saya bisa fokus menyelesaikan logika bisnis yang rumit
(validasi status berjenjang, perhitungan ongkir, COD) daripada belajar
framework baru di waktu ujian yang terbatas. Fitur bawaannya seperti migration,
Eloquent ORM, dan validasi request juga langsung cocok dengan kebutuhan soal
(database relasional dengan banyak relasi antar tabel). MySQL dipakai sebagai
database relasional sesuai syarat wajib soal, dan familiar untuk dikembangkan
maupun diperiksa strukturnya.

Untuk APK, saya memilih Ionic + Capacitor karena bisa memakai kembali
kemampuan HTML/CSS/JavaScript yang sudah saya kuasai dari sisi web, tanpa
harus mempelajari bahasa native Android (Kotlin/Java) dari nol dalam waktu
pengerjaan yang terbatas. Capacitor tetap menghasilkan aplikasi native yang
mengambil data lewat REST API (bukan WebView yang membuka halaman web),
sehingga tetap sesuai dengan syarat soal.

## 3. Cara menjalankan server
1. `composer install`
2. `cp .env.example .env` lalu `php artisan key:generate`
3. Buat database MySQL, misalnya `priangan_kilat`, lalu isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`, dan `DB_PORT` di `.env`
4. Isi `.env`: `PUSAT_WILAYAH_URL` dan `PUSAT_WILAYAH_API_KEY` sesuai layanan Pusat Wilayah yang tersedia
5. `php artisan migrate`
6. `php artisan db:seed` (membuat akun Admin, Manajer, dan tarif zona awal)
7. `npm install && npm run build`
8. `php artisan serve --host=0.0.0.0 --port=8000`
9. Login sebagai Admin → buka menu **Import Data Awal** → upload `pelanggan.csv`, `kurir.csv`, `paket_historis.csv` dari folder `UKK_RPL_2026-2027_CSV_Siswa/Varian_B_Ekspedisi/Data_Awal/`
10. Jalankan seeder demo: `php artisan db:seed --class=DemoSeeder`

## 4. Cara memasang APK

### Berkas APK

Berkas APK debug yang sudah dapat dipasang tersedia di:

```text
apk/kurir-ekspedisi.apk
```

Berkas ini disalin dari hasil build Ionic/Capacitor:

```bash
mkdir -p apk
cp apk-kurir-ekspedisi/android/app/build/outputs/apk/debug/app-debug.apk apk/kurir-ekspedisi.apk
```

APK mendukung Android API 26 atau Android 8.0 ke atas.

### Memasang lewat USB dan ADB

1. Aktifkan **Opsi Developer** dan **USB Debugging** pada HP.
2. Sambungkan HP ke PC melalui kabel USB.
3. Pastikan HP terdeteksi:

```bash
adb devices
```

4. Pasang APK:

```bash
adb install -r apk/kurir-ekspedisi.apk
```

Bila `adb` tidak tersedia pada PATH, gunakan jalur `platform-tools` di dalam instalasi Android SDK pada perangkat masing-masing, contoh:

```bash
<lokasi-android-sdk>/platform-tools/adb install -r apk/kurir-ekspedisi.apk
```

### Memasang lewat HP tanpa kabel

Salin berkas APK ke penyimpanan HP lalu buka melalui File Manager. Jika Android menampilkan peringatan sumber tidak dikenal, aktifkan izin pemasangan aplikasi dari sumber file tersebut.

### Menjalankan server API

Server Laravel harus berjalan dan dapat dijangkau dari HP:

```bash
cd project-ekspedisi
php artisan serve --host=0.0.0.0 --port=8000
```

PC dan HP harus berada pada jaringan Wi-Fi yang sama. Pada halaman login APK, masukkan alamat API laptop dengan format `http://<IP-LAN-laptop>:8000/api`, lalu tekan **Simpan Alamat**. Alamat disimpan di perangkat, sehingga saat jaringan berubah cukup perbarui alamat di aplikasi; APK tidak perlu dibuild ulang.

Cek IP LAN laptop saat itu dengan `ip a` (Linux) atau `ipconfig` (Windows). Contoh alamat API: `http://192.168.1.10:8000/api`.

Untuk Android emulator, masukkan alamat host khusus emulator pada halaman login:

```text
http://10.0.2.2:8000/api
```

Pastikan `android:usesCleartextTraffic="true"` tetap aktif pada manifest Android karena server uji memakai HTTP LAN.

## 5. Akun uji
| Peran | Identitas login | Password |
|---|---|---|
| Admin | admin@test.com | password |
| Manajer | manager@test.com | password |
| Kurir (contoh KUR-03) | Kode Kurir: KUR-03 | password123 (wajib ganti saat login pertama) |

## 6. Letak berkas
- Migrasi: `database/migrations/`
- Seeder akun & tarif awal: `database/seeders/AdminSeeder.php`, `ManagerSeeder.php`, `ZonaTarifSeeder.php` (otomatis jalan lewat `php artisan db:seed`)
- Impor data awal CSV: lewat UI Admin (`/admin/import`), logikanya di `app/Services/Import/`
- Seeder data demo: `database/seeders/DemoSeeder.php`, dijalankan manual setelah impor CSV data awal:
  `php artisan db:seed --class=DemoSeeder`
- Kurir yang disiapkan: `KUR-02`
- Jumlah paket awal: sampai maksimum 20 paket berstatus `Dikirim` milik `KUR-02`
- Seeder menggunakan nomor resi berawalan `PKE-DEMO-`, tidak menghapus paket impor atau paket di luar seeder, serta hanya menambah kekurangan paket hingga target 20
- Jangan menjalankan ulang seeder setelah ada paket demo yang diproses menjadi `Terkirim`, `Gagal Kirim`, atau `Retur`, karena aksi pengujian harus dilakukan langsung di aplikasi
- REST API Kurir: `routes/api.php`, controller di `app/Http/Controllers/Api/Kurir/`
- Project APK Kurir: folder `apk-kurir-ekspedisi/` (Ionic + Capacitor + Angular)

## 7. Keputusan Anda
- Format normalisasi nomor telepon (lihat `app/Services/TeleponNormalizer.php`)
- Penanganan baris CSV yang tidak bisa diproses (dilewati + dilaporkan, lihat `app/Services/Import/`)
- Penyamaran nomor telepon di cek resi (7 digit pertama + sisanya disamarkan)
- Pelanggan generik untuk paket demo (tidak ada data pengirim di `seed_paket_dikirim.csv`)
- **Zona tidak dikenali saat impor CSV paket historis:** jika Pusat Wilayah tidak mengenali kota tujuan saat proses impor `paket_historis.csv`, baris tersebut dilewati dan dicatat di ringkasan hasil impor (bukan diisi zona manual otomatis). Ini berbeda dari alur input paket manual, yang langsung meminta Admin memilih zona manual saat itu juga, karena saat impor CSV berjalan, tidak ada Admin yang bisa diminta mengambil keputusan untuk tiap baris secara langsung. Baris yang dilewati bisa diperiksa dan diinput ulang manual oleh Admin melalui form input paket biasa, yang sudah mendukung pilihan zona manual.
- **Alamat server APK diisi manual saat runtime** (disimpan di perangkat), bukan di-hardcode saat build. Di aplikasi produksi nyata, alamat server biasanya memakai domain tetap; pendekatan runtime ini dipilih khusus karena jaringan saat wawancara UKK tidak diketahui di muka, sehingga satu file APK yang sama (sesuai yang dikumpulkan di /apk) tetap bisa dipakai di jaringan manapun tanpa build ulang.

## 8. Lembar self-check

| No | Butir penerimaan | Klaim (Ya/Tidak) | Langkah uji singkat |
|---|---|---|---|
| 1 | Input paket, resi otomatis unik/reset harian, telepon divalidasi | Ya | Input paket baru, cek format resi PKE-YYMMDD-000x; coba input telepon tidak valid |
| 2 | Ongkir otomatis dari zona+berat, Express +50%, ongkir lama tidak berubah saat tarif diedit | Ya | Buat paket, lalu ubah tarif zona di menu Manajer, cek ongkir paket lama tetap sama |
| 3 | Status berjenjang, tidak boleh loncat, Terkirim/Retur final | Ya | Coba ubah status di luar urutan, pastikan ditolak |
| 4 | Riwayat tiap perubahan status (waktu, pelaku, catatan) | Ya | Buka detail paket, cek riwayat |
| 5 | Gagal kirim maksimal 2x, lalu wajib Retur oleh Admin | Ya | Tandai gagal kirim 2x dari APK/web kurir, cek tombol Retur muncul di Admin |
| 6 | Penugasan ke kurir, limit 20 paket, kurir nonaktif tidak bisa ditugaskan | Ya | Tugaskan 21 paket ke 1 kurir, pastikan ditolak di paket ke-21 |
| 7 | Cek resi publik tanpa login, telepon disamarkan | Ya | Buka `/` tanpa login, cek resi, pastikan telepon tersamar & tidak ada nama petugas |
| 8 | Biaya COD 2% (min Rp2.000), setoran per paket tidak bisa dobel | Ya | Buat paket COD, tandai Terkirim, catat setoran, coba catat lagi paket yang sama |
| 9 | Laporan pengiriman & pendapatan per zona (akses Manajer saja) | Ya | Buka laporan sebagai Admin (harus ditolak), lalu sebagai Manajer |
| 10 | Impor CSV data awal (resi ganda jadi satu, berat kosong ditandai, telepon dirapikan) | Ya | Jalankan impor, cek ringkasan hasil |
| 11 | Integrasi Pusat Wilayah + fallback zona manual saat API mati | Ya | Ubah API key jadi salah di `.env`, buat paket, pastikan masih bisa pilih zona manual |
| 12 | Akun kurir lengkap, wajib ganti password pertama kali | Ya | Login kurir baru di APK, pastikan diarahkan ganti password |
| 13 | Kurir ubah status Terkirim/Gagal Kirim | Ya | Login sebagai kurir di APK, update status paket |
| 14 | Kurir lihat paket COD Terkirim yang belum disetor | Ya | Login kurir di APK, cek daftar COD belum disetor |

### Fitur opsional

| No | Fitur opsional | Dikerjakan (Ya/Tidak) |
|---|---|---|
| O1 | Berat volumetrik (p×l×t ÷ 6000, dipakai bila lebih besar dari berat aktual) | Tidak |
| O2 | Asuransi opsional 0,5% dari nilai barang | Tidak |
| O3 | Laporan performa kurir (persentase terkirim/gagal) | Tidak |
| O4 | Penanda paket tertahan lebih dari 3 hari di status Transit | Tidak |
| O5 | Reset password lewat email (server email tiruan lokal) | Tidak |

## 9. Belum selesai / keterbatasan
Seluruh 14 butir penerimaan wajib sudah berjalan dan diuji sesuai langkah di atas. Kelima fitur opsional (O1–O5) belum dikerjakan pada versi ini.

ERD (docs/erd/ERD-Ekspedisi.png) belum mencantumkan kolom kode_zona di tabel zona_tarif dan tabel urutan_resi, karena keduanya ditambahkan setelah ERD awal digambar (untuk kebutuhan pencocokan zona dari API Pusat Wilayah dan penomoran resi harian). Fungsinya sudah berjalan di kode, hanya gambar ERD yang belum disinkronkan ulang.

## 10. Referensi dan Bantuan

Dalam proses pengembangan Sistem Informasi Manajemen Ekspedisi Priangan Kilat Express, saya memanfaatkan beberapa sumber referensi dan alat bantu untuk mendukung proses pembelajaran, perancangan, implementasi, serta penyelesaian kendala teknis.

**Tools AI yang digunakan:**

- **Claude (Anthropic)**
- **ChatGPT (OpenAI)**

**Bentuk bantuan yang diberikan:**

1. Membantu menganalisis kebutuhan sistem dan menentukan alur kerja aplikasi.
2. Membantu merancang struktur database, relasi antartabel, dan logika bisnis ekspedisi.
3. Membantu mengembangkan dan mengevaluasi beberapa controller Laravel.
4. Membantu implementasi REST API untuk kebutuhan komunikasi antara backend Laravel dan aplikasi Android berbasis Ionic.
5. Membantu memahami penggunaan middleware, autentikasi, validasi, dan pembatasan hak akses berdasarkan role pengguna.
6. Membantu menemukan dan memperbaiki error selama proses pengembangan.
7. Memberikan referensi serta penjelasan kode agar setiap fitur dapat dipahami dan dikembangkan kembali.