<?php

namespace App\Services;

class TeleponNormalizer
{
    // Mengembalikan null jika nomor tidak valid agar pemanggil dapat menolak input tersebut.
    public static function normalize(mixed $nomor): ?string
    {
        if (! is_string($nomor)) {
            return null;
        }

        // Pengguna sering menulis nomor dengan pemisah agar lebih mudah dibaca.
        $bersih = preg_replace('/[\s\-\(\)]/', '', $nomor);

        // Karakter selain angka dan awalan '+' membuat nomor tidak bisa dinormalisasi dengan aman.
        if (! preg_match('/^\+?[0-9]+$/', $bersih)) {
            return null;
        }

        // Satu format penyimpanan mencegah +62 dan 08 dianggap sebagai dua nomor berbeda.
        if (str_starts_with($bersih, '+62')) {
            $bersih = '0'.substr($bersih, 3);
        } elseif (str_starts_with($bersih, '62')) {
            $bersih = '0'.substr($bersih, 2);
        }

        // Batas minimum dihitung setelah semua pemisah dan awalan dirapikan.
        if (strlen($bersih) < 10) {
            return null;
        }

        return $bersih;
    }
}
