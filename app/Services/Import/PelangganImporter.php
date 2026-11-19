<?php

namespace App\Services\Import;

use App\Models\Pelanggan;
use App\Services\TeleponNormalizer;

class PelangganImporter
{
    /**
     * Mengimpor data pelanggan dari file CSV dan mengembalikan ringkasan hasilnya.
     *
     * @return array{total: int, berhasil: int, dilewati: array<int, string>}
     */
    public function import(string $path): array
    {
        $total = 0;
        $berhasil = 0;
        $dilewati = [];

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('File CSV pelanggan tidak dapat dibuka.');
        }

        // Header tidak diimpor karena hanya berisi nama-nama kolom CSV.
        fgetcsv($handle);

        while (($baris = fgetcsv($handle)) !== false) {
            $total++;

            // Kolom kota dibaca agar urutan CSV tetap benar, tetapi tidak disimpan karena pelanggan tidak memiliki kota tetap.
            [$nama, $telepon, $alamat, $kota] = array_pad($baris, 4, null);

            $teleponRapi = TeleponNormalizer::normalize($telepon);

            if ($teleponRapi === null) {
                $dilewati[] = "Baris {$total} ({$nama}): nomor telepon tidak valid ({$telepon}).";

                continue;
            }

            // Telepon menjadi kunci alami agar impor dapat dijalankan berulang tanpa membuat duplikat.
            Pelanggan::updateOrCreate(
                ['telepon' => $teleponRapi],
                [
                    'nama' => ucwords(strtolower(trim($nama))),
                    'alamat' => trim($alamat ?? ''),
                ]
            );

            $berhasil++;
        }

        fclose($handle);

        return ['total' => $total, 'berhasil' => $berhasil, 'dilewati' => $dilewati];
    }
}
