<?php

namespace App\Http\Controllers\Api\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\RiwayatPaket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenugasanController extends Controller
{
    // Daftar paket berstatus Dikirim milik kurir yang sedang login.
    public function index(): JsonResponse
    {
        $kurir = Kurir::where('pengguna_id', Auth::id())->firstOrFail();

        $paket = $kurir->paket()
            ->where('status', 'Dikirim')
            ->with('pengirim')
            ->latest()
            ->get()
            ->map(fn ($item) => $this->ringkas($item));

        return response()->json(['data' => $paket]);
    }

    // Detail satu paket. findOrFail lewat relasi $kurir->paket() memastikan
    // kurir lain tidak bisa buka paket yang bukan miliknya walau tahu ID-nya.
    public function show(string $id): JsonResponse
    {
        $kurir = Kurir::where('pengguna_id', Auth::id())->firstOrFail();

        $paket = $kurir->paket()
            ->with(['pengirim', 'zonaTarif', 'riwayat' => fn ($q) => $q->orderBy('created_at', 'asc')])
            ->findOrFail($id);

        return response()->json(['data' => $this->detail($paket)]);
    }

    // Mengubah status paket menjadi Terkirim atau Gagal Kirim.
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Terkirim,Gagal Kirim'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $kurir = Kurir::where('pengguna_id', Auth::id())->firstOrFail();
        $paket = $kurir->paket()->findOrFail($id);

        if ($paket->status !== 'Dikirim') {
            return response()->json([
                'message' => 'Status paket tidak dapat diubah.',
            ], 422);
        }

        if ($validated['status'] === 'Gagal Kirim' && $paket->jumlah_gagal >= 2) {
            return response()->json([
                'message' => 'Pengiriman sudah gagal dua kali. Paket menunggu proses Retur oleh Admin.',
            ], 422);
        }

        if ($validated['status'] === 'Gagal Kirim' && empty($validated['catatan'])) {
            return response()->json([
                'message' => 'Catatan kegagalan wajib diisi.',
                'errors' => ['catatan' => ['Catatan kegagalan wajib diisi.']],
            ], 422);
        }

        DB::transaction(function () use ($paket, $validated) {
            $statusSebelumnya = $paket->status;

            $paket->status = $validated['status'];

            if ($validated['status'] === 'Gagal Kirim') {
                $paket->jumlah_gagal++;
            }

            $paket->save();

            RiwayatPaket::create([
                'paket_id' => $paket->id,
                'pengguna_id' => Auth::id(),
                'status_dari' => $statusSebelumnya,
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Status paket berhasil diperbarui.',
            'data' => $this->ringkas($paket->fresh()),
        ]);
    }

    private function ringkas($paket): array
    {
        return [
            'id' => $paket->id,
            'no_resi' => $paket->no_resi,
            'penerima_nama' => $paket->penerima_nama,
            'penerima_alamat' => $paket->penerima_alamat,
            'kota_tujuan' => $paket->kota_tujuan,
            'layanan' => $paket->layanan,
            'nilai_cod' => (float) $paket->nilai_cod,
            'status' => $paket->status,
            'jumlah_gagal' => $paket->jumlah_gagal,
        ];
    }

    private function detail($paket): array
    {
        return array_merge($this->ringkas($paket), [
            'penerima_telepon' => $paket->penerima_telepon,
            'pengirim' => $paket->pengirim?->nama,
            'riwayat' => $paket->riwayat->map(fn ($r) => [
                'status' => $r->status,
                'catatan' => $r->catatan,
                'waktu' => $r->created_at,
            ]),
        ]);
    }
}