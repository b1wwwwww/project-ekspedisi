<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class PusatWilayahService
{
    public function ambilZona(string $kota): ?int
    {
        try {

            // Mengirim permintaan ke API Pusat Wilayah.
            $response = Http::withHeaders([
                'X-API-Key' => config('services.pusat_wilayah.api_key'),
            ])
                ->acceptJson()
                ->timeout(10)
                ->get(
                    config('services.pusat_wilayah.url') . '/wilayah',
                    ['kota' => $kota]
                );

            // Memeriksa keberhasilan respons API.
            if (!$response->successful()) {
                return null;
            }

            // Mengambil kode zona dari respons JSON.
            $zona = $response->json('zona');

            // Memastikan kode zona berupa angka.
            if (!is_numeric($zona)) {
                return null;
            }

            return (int) $zona;
            
        } catch (ConnectionException $e) {

            // Menangani kegagalan koneksi API.
            return null;

        } catch (\Throwable $e) {

            // Menangani kesalahan lain saat proses API.
            report($e);

            return null;
        }
    }
}