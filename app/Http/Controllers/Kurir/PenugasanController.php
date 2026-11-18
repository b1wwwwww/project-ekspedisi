<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\RiwayatPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenugasanController extends Controller
{
    // Menampilkan daftar paket yang ditugaskan kepada Kurir.
    public function index()
    {
        // Mengambil akun pengguna yang sedang login.
        $pengguna = Auth::user();

        // Mencari data Kurir berdasarkan akun yang sedang login.
        $kurir = Kurir::where('pengguna_id', $pengguna->id)->firstOrFail();

        // Mengambil semua paket yang ditugaskan kepada Kurir tersebut.
        $paket = $kurir->paket()->latest()->get();

        return view('kurir.penugasan.index', compact('paket'));
    }

    // Menampilkan detail paket yang ditugaskan kepada Kurir.
    public function show($id)
    {
        // Mengambil akun pengguna yang sedang login.
        $pengguna = Auth::user();

        // Mencari data Kurir berdasarkan akun pengguna.
        $kurir = Kurir::where('pengguna_id', $pengguna->id)->firstOrFail();

        // Mengambil paket berdasarkan ID dan memastikan paket tersebut milik Kurir yang login.
        $paket = $kurir->paket()
            ->with(['pengirim', 'zonaTarif', 'riwayat'])
            ->findOrFail($id);

        return view('kurir.penugasan.show', compact('paket'));
    }

    // Mengubah status paket oleh Kurir.
    public function updateStatus(Request $request, $id)
    {
        // Memvalidasi input dari form.
        $validated = $request->validate([
            'status' => ['required', 'in:Terkirim,Gagal Kirim'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        // Mengambil data Kurir yang sedang login.
        $kurir = Kurir::where('pengguna_id', Auth::id())->firstOrFail();

        // Memastikan paket merupakan penugasan Kurir tersebut.
        $paket = $kurir->paket()->findOrFail($id);

        // Status hanya boleh diubah ketika paket sedang dikirim.
        if ($paket->status !== 'Dikirim') {
            return back()->with('error', 'Status paket tidak dapat diubah.');
        }

        // Catatan wajib diisi jika pengiriman gagal.
        if (
            $validated['status'] === 'Gagal Kirim' &&
            empty($validated['catatan'])
        ) {
            return back()
                ->withErrors(['catatan' => 'Catatan kegagalan wajib diisi.'])
                ->withInput();
        }

        DB::transaction(function () use ($paket, $validated) {

            // Menyimpan status sebelumnya.
            $statusSebelumnya = $paket->status;

            // Mengubah status paket.
            $paket->status = $validated['status'];

            // Menambah jumlah kegagalan jika pengiriman gagal.
            if ($validated['status'] === 'Gagal Kirim') {
                $paket->jumlah_gagal++;
            }

            $paket->save();

            // Menyimpan riwayat perubahan status.
            RiwayatPaket::create([
                'paket_id' => $paket->id,
                'pengguna_id' => Auth::id(),
                'status_dari' => $statusSebelumnya,
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]);
        });

        return redirect()
            ->route('kurir.penugasan.show', $paket->id)
            ->with('success', 'Status paket berhasil diperbarui.');
    }
}
