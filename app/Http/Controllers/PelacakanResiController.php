<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\ZonaTarif;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelacakanResiController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'resi' => ['nullable', 'string', 'max:255'],
        ]);

        $paket = null;
        $teleponSamar = null;
        $resi = strtoupper(trim((string) $request->query('resi', '')));

        if ($resi !== '') {
            $paket = Paket::query()
                ->where('no_resi', $resi)
                ->with(['riwayat' => function ($query) {
                    $query->latest('created_at')->with('pengguna');
                }])
                ->first();

            if ($paket) {
                // Menyamarkan nomor telepon: 7 digit pertama tampil, sisanya disamarkan
                $telepon = $paket->penerima_telepon ?? '';
                $teleponSamar = substr($telepon, 0, 7).str_repeat('*', max(0, strlen($telepon) - 7));
            }
        }

        $zonaTarif = rescue(fn () => ZonaTarif::orderBy('kode_zona')->get(), collect());

        return view('welcome', compact('paket', 'resi', 'teleponSamar', 'zonaTarif'));
    }
}
