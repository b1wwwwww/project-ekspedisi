<?php

namespace App\Services\Import;

use App\Models\Kurir;
use App\Models\User;
use App\Services\TeleponNormalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KurirImporter
{
    /**
     * Mengimpor akun dan profil kurir dari file CSV.
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
            throw new \RuntimeException('File CSV kurir tidak dapat dibuka.');
        }

        fgetcsv($handle);

        while (($baris = fgetcsv($handle)) !== false) {
            $total++;

            [$kodeKurir, $nama, $telepon] = array_pad($baris, 3, null);
            $teleponRapi = TeleponNormalizer::normalize($telepon);

            if (blank($kodeKurir) || blank($nama) || $teleponRapi === null) {
                $dilewati[] = "Baris {$total} ({$kodeKurir}): data kurir atau nomor telepon tidak valid.";

                continue;
            }

            $kurirDenganKodeSama = Kurir::query()
                ->with('pengguna')
                ->where('kode_kurir', trim($kodeKurir))
                ->first();

            $penggunaDenganTeleponSama = User::query()
                ->where('telepon', $teleponRapi)
                ->first();

            if (
                $penggunaDenganTeleponSama !== null &&
                $penggunaDenganTeleponSama->role !== 'kurir' &&
                ($kurirDenganKodeSama === null || $kurirDenganKodeSama->pengguna_id !== $penggunaDenganTeleponSama->id)
            ) {
                $dilewati[] = "Baris {$total} ({$kodeKurir}): nomor telepon sudah digunakan oleh akun selain kurir.";

                continue;
            }

            DB::transaction(function () use ($kurirDenganKodeSama, $penggunaDenganTeleponSama, $kodeKurir, $nama, $teleponRapi): void {
                $pengguna = $kurirDenganKodeSama?->pengguna ?? $penggunaDenganTeleponSama;

                if ($pengguna === null) {
                    $pengguna = User::create([
                        'nama' => $this->rapikanNama($nama),
                        'telepon' => $teleponRapi,
                        // Password sementara harus diganti saat kurir pertama kali masuk ke APK.
                        'password' => Hash::make('password123'),
                        'role' => 'kurir',
                        'wajib_ganti_password' => true,
                    ]);
                } else {
                    $pengguna->update([
                        'nama' => $this->rapikanNama($nama),
                        'telepon' => $teleponRapi,
                        'role' => 'kurir',
                    ]);
                }

                Kurir::updateOrCreate(
                    ['kode_kurir' => trim($kodeKurir)],
                    [
                        'pengguna_id' => $pengguna->id,
                        'area_tugas' => '-',
                        'aktif' => true,
                    ]
                );
            });

            $berhasil++;
        }

        fclose($handle);

        return ['total' => $total, 'berhasil' => $berhasil, 'dilewati' => $dilewati];
    }

    private function rapikanNama(string $nama): string
    {
        return ucwords(strtolower(trim($nama)));
    }
}
