<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class PusatWilayahService
{
    public function ambilZona(string $kota): ?int
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.pusat_wilayah.api_key'),
            ])
                ->acceptJson()
                ->timeout(10)
                ->get(
                    config('services.pusat_wilayah.url') . '/wilayah',
                    ['kota' => $kota]
                );

            // Pemeriksaan keberhasilan API
            if (!$response->successful()) {
                return null;
            }

            // Pemeriksaan zona
            $zona = $response->json('zona');

            if (!is_numeric($zona)) {
                return null;
            }

            return (int) $zona;

        // Penanganan masalah koneksi
        } catch (ConnectionException $e) {
            return null;
        }
    }
}
