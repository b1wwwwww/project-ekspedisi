<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\ZonaTarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    /**
     * Menampilkan seluruh zona dan tarif.
     */
    public function index()
    // Mengambil seluruh zona dari database, mengurutkannya berdasarkan kode,
    // lalu mengirim data ke halaman tarif.index.
    {
        $zonaTarif = ZonaTarif::orderBy('kode_zona')->get();

        return view('tarif.index', compact('zonaTarif'));
    }

    /**
     * Mengubah tarif per kilogram.
     */
    public function update(Request $request, ZonaTarif $zonaTarif)
    {
        $validated = $request->validate([
            'harga_per_kg' => 'required|numeric|min:1',
        ], [
            'harga_per_kg.required' => 'Tarif per kilogram wajib diisi.',
            'harga_per_kg.numeric' => 'Tarif harus berupa angka.',
            'harga_per_kg.min' => 'Tarif minimal Rp1.',
        ]);

        $zonaTarif->update([
            'harga_per_kg' => $validated['harga_per_kg'], // tarif baru
            'diubah_oleh' => $request->user()->id, // manajer yang mengubah tarif
        ]);

        return redirect()
            ->route('tarif.index')
            ->with('success', 'Tarif zona berhasil diperbarui.');
    }
}