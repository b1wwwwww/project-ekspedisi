<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    // menerima Request agar bisa membaca kata kunci pencarian dari URL.
    {
        $query = Pelanggan::withCount('paket'); // menghitung jumlah paket milik setiap pelanggan.

        // Pencarian pelanggan
        if ($request->filled('q')) {
            $keyword = $request->q;

            // pencarian berdasarkan nama dan telepon
            $query->where(function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('telepon', 'like', "%{$keyword}%");
            });
        }

        $pelanggan = $query
            ->orderBy('nama')
            ->paginate(10) // menampilkan 10 pelanggan per halaman
            ->withQueryString();

        return view('pelanggan.index', compact('pelanggan'));
    }
}