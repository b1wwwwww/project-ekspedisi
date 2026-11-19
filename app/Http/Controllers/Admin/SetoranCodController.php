<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Paket;
use App\Models\SetoranCod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoranCodController extends Controller
{
    // Menampilkan daftar kurir beserta total COD yang belum di setor.
    public function index(Request $request)
    {
        $kurir = Kurir::with('pengguna')
            ->withSum(['paket as cod_belum_disetor' => function ($query){
                $query->where('status', 'Terkirim')
                    ->whereDoesntHave('detailSetoranCod');
            }], 'nilai_cod')
            ->get();

        // jika Admin memilih satu kurir, tampilkan paket COD yang belum disetor
        $kurirDipilih = null;
        $paketBelumDisetor = collect();

        if ($request->filled('kurir_id')) {
            $kurirDipilih = Kurir::with('pengguna')->findOrFail($request->kurir_id);

            $paketBelumDisetor = Paket::where('kurir_id', $kurirDipilih->id)
                ->where('status', 'Terkirim')
                ->where('nilai_cod', '>', 0)
                ->whereDoesntHave('detailSetoranCod')
                ->get();
        }

        return view('admin.setoran-cod.index', compact(
            'kurir',
            'kurirDipilih',
            'paketBelumDisetor'
        ));
    }

    // Mencatat setoran COD untuk paket-paket terpilih.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kurir_id'    => ['required', 'integer', 'exists:kurir,id'],
            'paket_id'    => ['required', 'array', 'min:1'],
            'paket_id.*'  => ['required', 'integer', 'exists:paket,id'],
        ]); 

        DB::transaction(function () use ($validated) {

            // Mengunci paket terpilih agar tidak disetor dua kali secara bersamaan.
            $paket = Paket::where('kurir_id', $validated['kurir_id'])
                ->where('status', 'Terkirim')
                ->where('nilai_cod', '>', 0)
                ->whereIn('id', $validated['paket_id'])
                ->whereDoesntHave('detailSetoranCod')
                ->lockForUpdate()
                ->get();

            if($paket->isEmpty()) {
                abort(422, 'Tidak ada paket yang dapat disetor');
            }

            // Membuat satu transaksi setoran COD.
            $setoran = SetoranCod::create([
                'kurir_id' => $validated['kurir_id'],
                'dicatat_oleh' => auth()->id(),
                'tanggal_setoran' => now(),
                'total_setoran' => $paket->sum('nilai_cod'),
            ]);

            // Membuat detail setoran untuk setiap paket.
            foreach ($paket as $item) {
                $setoran->detail()->create([
                    'paket_id' => $item->id,
                    'jumlah_cod' => $item->nilai_cod,
                ]);
            }
        });

        return redirect()
            ->route('admin.setoran-cod.index')
            ->with('success', 'Setoran COD berhasil dicatat.');
    }
}
