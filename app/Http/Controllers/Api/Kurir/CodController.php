<?php

namespace App\Http\Controllers\Api\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Paket;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CodController extends Controller
{
    // Paket COD berstatus Terkirim yang diantar kurir ini dan belum disetor.
    public function belumDisetor(): JsonResponse
    {
        $kurir = Kurir::where('pengguna_id', Auth::id())->firstOrFail();

        $paket = Paket::where('kurir_id', $kurir->id)
            ->where('status', 'Terkirim')
            ->where('nilai_cod', '>', 0)
            ->whereDoesntHave('detailSetoranCod')
            ->orderBy('tanggal_masuk')
            ->get(['id', 'no_resi', 'penerima_nama', 'kota_tujuan', 'nilai_cod']);

        return response()->json([
            'total_belum_disetor' => (float) $paket->sum('nilai_cod'),
            'jumlah_paket' => $paket->count(),
            'data' => $paket,
        ]);
    }
}