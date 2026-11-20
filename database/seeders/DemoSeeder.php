<?php

namespace Database\Seeders;

use App\Models\Kurir;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\RiwayatPaket;
use App\Models\User;
use App\Models\ZonaTarif;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    // Target jumlah paket Dikirim yang harus dimiliki kurir demo.
    private const TARGET_PAKET_DIKIRIM = 20;

    // Awalan nomor resi khusus milik seeder ini, supaya mudah dibedakan
    // dari resi hasil input manual/impor dan aman dijalankan berulang.
    private const PREFIX_RESI = 'PKE-DEMO-';

    public function run(): void
    {
        $kurir = Kurir::where('kode_kurir', 'KUR-02')->first();

        if (! $kurir) {
            $this->command->warn('KUR-02 tidak ditemukan. Jalankan impor data awal (kurir.csv) terlebih dahulu.');

            return;
        }

        // Menghitung paket Dikirim yang BUKAN dari seeder ini (misal dari impor data awal).
        $jumlahDiLuarSeeder = Paket::where('kurir_id', $kurir->id)
            ->where('status', 'Dikirim')
            ->where('no_resi', 'not like', self::PREFIX_RESI.'%')
            ->count();

        $jumlahDibutuhkan = max(0, self::TARGET_PAKET_DIKIRIM - $jumlahDiLuarSeeder);

        if ($jumlahDibutuhkan === 0) {
            $this->command->info("KUR-02 sudah memiliki {$jumlahDiLuarSeeder} paket Dikirim. Seeder tidak menambah apapun.");

            return;
        }

        // Pelanggan generik sebagai pengirim seluruh paket demo (CSV seeder tidak menyediakan data pengirim).
        $pengirimDemo = Pelanggan::firstOrCreate(
            ['telepon' => '081200000000'],
            ['nama' => 'Pelanggan Demo UKK', 'alamat' => 'Alamat Demo']
        );

        $admin = User::where('role', 'admin')->firstOrFail();

        $path = base_path('UKK_RPL_2026-2027_CSV_Siswa/Varian_B_Ekspedisi/Data_Seeder_CSV/seed_paket_dikirim.csv');

        if (! file_exists($path)) {
            $this->command->error("File seeder tidak ditemukan di:\n{$path}\nPastikan folder 'UKK_RPL_2026-2027_CSV_Siswa' sudah disalin ke root project.");
            return;
        }

        $handle = fopen($path, 'r');
        fgetcsv($handle); // lewati header

        $nomor = 0;

        while ($nomor < $jumlahDibutuhkan && ($baris = fgetcsv($handle)) !== false) {
            $nomor++;

            [$kodeKurir, $penerima, $teleponPenerima, $alamatPenerima, $kotaTujuan, $zona, $beratKg, $layanan] = $baris;

            $zonaTarif = ZonaTarif::where('kode_zona', (int) $zona)->firstOrFail();
            $beratTagih = max(1, ceil((float) $beratKg));
            $ongkir = $beratTagih * $zonaTarif->harga_per_kg;

            if ($layanan === 'Express') {
                $ongkir = (int) ($ongkir * 1.5);
            }

            $noResi = self::PREFIX_RESI.str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);

            DB::transaction(function () use ($noResi, $pengirimDemo, $kurir, $admin, $zonaTarif, $penerima, $teleponPenerima, $alamatPenerima, $kotaTujuan, $beratKg, $beratTagih, $ongkir, $layanan) {
                $paket = Paket::updateOrCreate(
                    ['no_resi' => $noResi],
                    [
                        'tanggal_masuk' => now()->toDateString(),
                        'pengirim_id' => $pengirimDemo->id,
                        'kurir_id' => $kurir->id,
                        'dibuat_oleh' => $admin->id,
                        'zona_tarif_id' => $zonaTarif->id,
                        'penerima_nama' => $penerima,
                        'penerima_telepon' => $teleponPenerima,
                        'penerima_alamat' => $alamatPenerima,
                        'kota_tujuan' => $kotaTujuan,
                        'berat_kg' => (float) $beratKg,
                        'berat_tagih' => $beratTagih,
                        'tarif_per_kg' => $zonaTarif->harga_per_kg,
                        'ongkir' => $ongkir,
                        'layanan' => $layanan,
                        'nilai_cod' => 0,
                        'biaya_cod' => 0,
                        'status' => 'Dikirim',
                        'jumlah_gagal' => 0,
                    ]
                );

                RiwayatPaket::updateOrCreate(
                    ['paket_id' => $paket->id, 'catatan' => 'Dibuat oleh seeder data demo.'],
                    [
                        'pengguna_id' => $admin->id,
                        'status_dari' => 'Transit',
                        'status' => 'Dikirim',
                    ]
                );
            });
        }

        fclose($handle);

        $this->command->info("Seeder demo menambahkan {$nomor} paket Dikirim untuk KUR-02 (total sekarang: ".($jumlahDiLuarSeeder + $nomor).').');
    }
}
