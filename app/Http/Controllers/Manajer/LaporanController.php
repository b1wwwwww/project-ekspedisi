<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Paket;
use App\Models\DetailSetoranCod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    // Menentukan dan memvalidasi rentang tanggal laporan.
    private function rentangTanggal(Request $request): array
    {
        $validated = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ], [
            'dari.date' => 'Format tanggal awal tidak valid.',
            'sampai.date' => 'Format tanggal akhir tidak valid.',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
        ]);

        $dari = !empty($validated['dari'])
            ? Carbon::parse($validated['dari'])->startOfDay()
            : Carbon::now()->startOfMonth();

        $sampai = !empty($validated['sampai'])
            ? Carbon::parse($validated['sampai'])->endOfDay()
            : Carbon::now()->endOfMonth();

        return [$dari, $sampai];
    }

    // Laporan Pengiriman: jumlah paket berdasarkan status saat ini.
    public function pengiriman(Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $rekap = Paket::whereBetween('tanggal_masuk', [$dari, $sampai])
        // mengelompokkan paket berdasarkan status, kemudian menghitung jumlah paket pada setiap kelompok.
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->get();

        $totalPaket = $rekap->sum('jumlah');

        return view('manajer.laporan.pengiriman', compact(
            'rekap',
            'totalPaket',
            'dari',
            'sampai'
        ));
    }

    // Laporan Pendapatan per Zona: total ongkir dan biaya COD.
    public function pendapatanZona(Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $rekap = Paket::whereBetween('paket.tanggal_masuk', [$dari, $sampai])
            ->join('zona_tarif', 'zona_tarif.id', '=', 'paket.zona_tarif_id')
            // menjumlahkan ongkir dan biaya COD berdasarkan zona.
            ->selectRaw('
                zona_tarif.id,
                zona_tarif.nama_zona,
                COUNT(paket.id) as jumlah_paket,
                SUM(paket.ongkir) as total_ongkir,
                SUM(paket.biaya_cod) as total_biaya_cod
            ')
            ->groupBy('zona_tarif.id', 'zona_tarif.nama_zona')
            ->orderBy('zona_tarif.id')
            ->get();

        $totalPendapatan = $rekap->sum(
            fn ($item) => $item->total_ongkir + $item->total_biaya_cod
        );

        return view('manajer.laporan.pendapatan-zona', compact(
            'rekap',
            'totalPendapatan',
            'dari',
            'sampai'
        ));
    }

    // Rekap COD per Kurir: menghitung setoran sepanjang waktu.
    public function rekapCod()
    {
        $codSudahDisetor = DetailSetoranCod::query()
            ->join('setoran_cod', 'setoran_cod.id', '=', 'detail_setoran_cod.setoran_id')
            ->selectRaw('
                setoran_cod.kurir_id,
                SUM(detail_setoran_cod.jumlah_cod) as total_cod_disetor
            ')
            ->groupBy('setoran_cod.kurir_id');

        $kurir = Kurir::with('pengguna')
            ->leftJoinSub($codSudahDisetor, 'rekap_setoran', function ($join) {
                $join->on('rekap_setoran.kurir_id', '=', 'kurir.id');
            })
            ->select('kurir.*')
            ->selectRaw('COALESCE(rekap_setoran.total_cod_disetor, 0) as cod_sudah_disetor')
            ->withSum(['paket as cod_belum_disetor' => function ($query) {
                $query->where('status', 'Terkirim')
                    ->where('nilai_cod', '>', 0)
                    ->whereDoesntHave('detailSetoranCod');
            }], 'nilai_cod')
            ->get();

        return view('manajer.laporan.rekap-cod', compact('kurir'));
    }
}