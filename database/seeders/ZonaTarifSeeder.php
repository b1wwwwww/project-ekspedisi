<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZonaTarif;

class ZonaTarifSeeder extends Seeder
{
    public function run(): void
    {
        $dataZona = [
            [
                'kode_zona' => 1,
                'nama_zona' => 'Bandung Raya',
                'harga_per_kg' => 8000,
                'cakupan_wilayah' => 'Bandung Raya',
            ],
            [
                'kode_zona' => 2,
                'nama_zona' => 'Jawa Barat lainnya',
                'harga_per_kg' => 12000,
                'cakupan_wilayah' => 'Wilayah Jawa Barat selain Bandung Raya',
            ],
            [
                'kode_zona' => 3,
                'nama_zona' => 'Luar Jawa Barat',
                'harga_per_kg' => 20000,
                'cakupan_wilayah' => 'Wilayah di luar Jawa Barat',
            ],
        ];

        foreach ($dataZona as $zona) {
            ZonaTarif::updateOrCreate(
                ['kode_zona' => $zona['kode_zona']],
                $zona
            );
        }
    }
}