<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'max:20', 'unique:pelanggan,telepon'],
            'alamat' => ['nullable', 'string'],
        ]);

        Pelanggan::create($validated);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }
}