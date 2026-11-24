<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Paket;
use App\Models\Kurir;

class PenugasanKurirController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil paket yang siap ditugaskan atau ditugaskan ulang.
        $query = Paket::with([
            'pengirim',
            'kurir.pengguna',
            'zonaTarif',
            'riwayat' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
        ])
            ->where(function ($query) {
                $query->where(function ($query) {
                    // Semua paket Transit siap ditugaskan, terlepas dari kurir_id lama
                    // yang mungkin berasal dari impor atau data historis.
                    $query->where('status', 'Transit');
                })->orWhere(function ($query) {
                    $query->where('status', 'Gagal Kirim')
                        ->where('jumlah_gagal', 1);
                });
            });

        // Filter berdasarkan zona tarif.
        if ($request->filled('zona') && $request->zona !== 'ALL') {
            $query->whereHas('zonaTarif', function ($query) use ($request) {
                $query->where('kode_zona', $request->zona);
            });
        }

        $paket = $query->orderBy('tanggal_masuk', 'asc')->get();

        // Mengambil seluruh kurir aktif dan menghitung paket yang sedang dikirim.
        $kurir = Kurir::with('pengguna')
            ->where('aktif', true)
            ->withCount([
                'paket as paket_dikirim_count' => function ($query) {
                    $query->where('status', 'Dikirim');
                }
            ])
            ->get();

        return view('penugasan-kurir.index', compact(
            'paket',
            'kurir'
        ));
    }



    public function store(Request $request)
    {
        // Memvalidasi data yang dikirim dari form.
        $validated = $request->validate([
            'paket_id' => ['required', 'array', 'min:1'],
            'paket_id.*' => ['required', 'integer', 'distinct', 'exists:paket,id'],
            'kurir_id' => ['required', 'integer', 'exists:kurir,id'],
        ]);

        DB::transaction(function () use ($validated) {

            // Mengunci data kurir agar kapasitas tidak berubah
            // akibat penugasan lain secara bersamaan.
            $kurir = Kurir::with('pengguna')
                ->where('id', $validated['kurir_id'])
                ->lockForUpdate()
                ->first();

            if (!$kurir || !$kurir->aktif) {
                throw ValidationException::withMessages([
                    'kurir_id' => 'Kurir tidak aktif atau tidak ditemukan.',
                ]);
            }

            // Mengambil dan mengunci paket yang dipilih.
            $paket = Paket::with('kurir.pengguna')
                ->whereIn('id', $validated['paket_id'])
                ->lockForUpdate()
                ->get();

            // Memastikan semua paket ditemukan.
            if ($paket->count() !== count($validated['paket_id'])) {
                throw ValidationException::withMessages([
                    'paket_id' => 'Beberapa paket tidak ditemukan.',
                ]);
            }

            // Memeriksa kelayakan setiap paket.
            foreach ($paket as $item) {

                // Paket Transit siap ditugaskan walau masih menyimpan kurir_id lama.
                $siapDitugaskan = $item->status === 'Transit';

                $siapDitugaskanUlang =
                    $item->status === 'Gagal Kirim' && $item->jumlah_gagal == 1;

                if (!$siapDitugaskan && !$siapDitugaskanUlang) {
                    throw ValidationException::withMessages([
                        'paket_id' => "Paket {$item->no_resi} tidak dapat ditugaskan.",
                    ]);
                }


            }

            // Menghitung kapasitas kurir yang sedang digunakan.
            $kapasitasTerpakai = Paket::where('kurir_id', $kurir->id)
                ->where('status', 'Dikirim')
                ->count();

            $sisaKapasitas = 20 - $kapasitasTerpakai;

            // Memastikan kapasitas kurir mencukupi.
            if ($paket->count() > $sisaKapasitas) {
                throw ValidationException::withMessages([
                    'kurir_id' => "Kapasitas kurir tidak mencukupi. Sisa kapasitas: {$sisaKapasitas} paket.",
                ]);
            }

            // Memproses penugasan setiap paket.
            foreach ($paket as $item) {

                $statusSebelumnya = $item->status;

                $kurirSebelumnya = $item->kurir;

                // Menentukan catatan penugasan.
                if ($statusSebelumnya === 'Transit') {
                    $catatan = "Paket ditugaskan kepada kurir {$kurir->kode_kurir} - {$kurir->pengguna->nama}.";
                } else {
                    $namaKurirLama = $kurirSebelumnya
                        ? "{$kurirSebelumnya->kode_kurir} - {$kurirSebelumnya->pengguna->nama}"
                        : 'Tidak diketahui';

                    $catatan = "Penugasan ulang dari kurir {$namaKurirLama} kepada kurir {$kurir->kode_kurir} - {$kurir->pengguna->nama}.";
                }

                // Memperbarui data paket.
                $item->kurir_id = $kurir->id;
                $item->status = 'Dikirim';
                $item->save();

                // Menyimpan riwayat penugasan.
                $item->riwayat()->create([
                    'pengguna_id' => auth()->id(),
                    'status_dari' => $statusSebelumnya,
                    'status' => 'Dikirim',
                    'catatan' => $catatan,
                ]);
            }
        });

        return redirect()
            ->route('admin.penugasan-kurir.index')
            ->with('success', 'Paket berhasil ditugaskan kepada kurir.');
    }
}