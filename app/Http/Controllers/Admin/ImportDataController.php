<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Import\KurirImporter;
use App\Services\Import\PaketHistorisImporter;
use App\Services\Import\PelangganImporter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportDataController extends Controller
{
    public function index(): View
    {
        return view('admin.import.index');
    }

    public function store(
        Request $request,
        PelangganImporter $pelangganImporter,
        KurirImporter $kurirImporter,
        PaketHistorisImporter $paketHistorisImporter,
    ): View {
        $request->validate([
            'file_pelanggan' => ['required', 'file', 'mimes:csv,txt'],
            'file_kurir' => ['required', 'file', 'mimes:csv,txt'],
            'file_paket_historis' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        // Urutan ini menjaga relasi paket: pelanggan dan kurir harus tersedia sebelum paket historis diimpor.
        $hasilPelanggan = $pelangganImporter->import((string) $request->file('file_pelanggan')->getRealPath());
        $hasilKurir = $kurirImporter->import((string) $request->file('file_kurir')->getRealPath());
        $hasilPaketHistoris = $paketHistorisImporter->import(
            (string) $request->file('file_paket_historis')->getRealPath(),
            (int) $request->user()->id,
        );

        return view('admin.import.hasil', compact('hasilPelanggan', 'hasilKurir', 'hasilPaketHistoris'));
    }
}
