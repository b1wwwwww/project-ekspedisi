<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Paket; // mengambil data dan menghitung statistik paket.
use App\Models\DetailSetoranCod; // menghitung COD yang sudah disetor.
use Illuminate\Support\Carbon; // membantu menentukan periode bulan berjalan.

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Manajer.
     */
    public function index()
    {
    // Statistik seluruh paket.
    $totalPaket = Paket::count();

    // Paket yang berhasil sampai ke penerima.
    $paketTerkirim = Paket::where('status', 'Terkirim')->count();

    // Paket yang masih dalam proses operasional.
    $paketDalamProses = Paket::whereIn('status', [
        'Diterima',
        'Transit',
        'Dikirim',
    ])->count();

    // Paket yang gagal dikirim.
    $paketGagal = Paket::where('status', 'Gagal Kirim')->count();

    // Paket yang dikembalikan.
    $paketRetur = Paket::where('status', 'Retur')->count();


        // Periode bulan berjalan.
    $awalBulan = Carbon::now()->startOfMonth()->toDateString();
    $akhirBulan = Carbon::now()->endOfMonth()->toDateString();

    // Total pendapatan ongkir bulan ini.
    $totalPendapatanOngkir = Paket::whereBetween('tanggal_masuk', [
        $awalBulan,
        $akhirBulan,
    ])->sum('ongkir');

    // Total pendapatan biaya COD bulan ini.
    $totalBiayaCod = Paket::whereBetween('tanggal_masuk', [
        $awalBulan,
        $akhirBulan,
    ])->sum('biaya_cod');

    // Total nilai COD yang sudah disetor bulan ini.
    $totalCodDisetor = DetailSetoranCod::whereHas('setoran', function ($query) use ($awalBulan, $akhirBulan) {
    $query->whereBetween('tanggal_setoran', [
        $awalBulan,
        $akhirBulan,
    ]);
    })->sum('jumlah_cod');

    // Total nilai COD yang belum disetor bulan ini.
    $totalCodBelumDisetor = Paket::whereBetween('tanggal_masuk', [
        $awalBulan,
        $akhirBulan,
    ])
        ->where('status', 'Terkirim')
        ->where('nilai_cod', '>', 0)
        ->whereDoesntHave('detailSetoranCod')
        ->sum('nilai_cod');

        return view('manajer.dashboard', compact(
        'totalPaket',
        'paketTerkirim',
        'paketDalamProses',
        'paketGagal',
        'paketRetur',
        'totalPendapatanOngkir',
        'totalBiayaCod',
        'totalCodDisetor',
        'totalCodBelumDisetor'
    ));
    }
}