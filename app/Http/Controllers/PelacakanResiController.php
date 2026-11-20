<?php

namespace App\Http\Controllers;

use App\Models\Paket;
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
                        $query->latest('created_at');
                    },
                ])
                ->first();

        if($paket){
            // menyamarkan nomor telepon: 3 tigit pertama dan akhir tampil, sisanya di hide
            $teleponSamar = substr($paket->penerima_telepon, 0, 7) . str_repeat('*', max(0, strlen($paket->penerima_telepon) - 7));
            }
        }

        return view('welcome', compact('paket', 'resi', 'teleponSamar'));
    }
}
