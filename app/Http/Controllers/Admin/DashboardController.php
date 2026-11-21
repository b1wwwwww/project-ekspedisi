<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Kurir;


class DashboardController extends Controller
{
    public function index()
    {
        // Paket masuk dihitung pada hari ini; dashboard juga menyediakan
        // tanggal dan jumlah paket terbaru agar data demo tidak tampil nol hanya karena tanggal demo berbeda.
        $paketMasukHariIni = Paket::whereDate('tanggal_masuk', today())
            ->count();

        $tanggalPaketTerakhir = Paket::max('tanggal_masuk');
        $paketMasukTerakhir = $tanggalPaketTerakhir
            ? Paket::whereDate('tanggal_masuk', $tanggalPaketTerakhir)->count()
            : 0;

        // Menunggu penugasan Kurir
        $menungguPenugasan = Paket::where('status', 'Transit')
            ->whereNull('kurir_id')
            ->count();

        // Paket yang sedang dikirim oleh kurir.
        $sedangDiKirim = Paket::where('status', 'Dikirim')
            ->count();

        // Perlu tindakan rerur.
        $perluTindakanRetur = Paket::where('jumlah_gagal', '>=', 2)
            ->where('status', 'Gagal Kirim')
            ->count();

        // Paket yang membutuhkan tindakan operasional.
        $antreanOperasional = Paket::where(function ($query) {
            $query->where('status', 'Diterima')

                ->orWhere(function ($query) {
                    $query->where('status', 'Transit')
                        ->whereNull('kurir_id');
                })

                ->orWhere(function ($query) {
                    $query->where('status', 'Gagal Kirim')
                        ->where('jumlah_gagal', '>=', 2);
                });
        })
            ->orderBy('tanggal_masuk', 'asc')
            ->limit(5)
            ->get();


        // Kapasitas Kurir
        $kapasitasKurir = Kurir::with('pengguna')
            ->where('aktif', true)
            ->withCount([
                'paket as paket_dikirim_count' => function ($query) {
                    $query->where('status', 'Dikirim');
                }
            ])
            ->get();

        $codBelumDisetor = Paket::where('status', 'Terkirim')
            ->where('nilai_cod', '>', 0)
            ->whereDoesntHave('detailSetoranCod')
            ->sum('nilai_cod');

        return view('admin.dashboard', compact(
            'paketMasukHariIni',
            'tanggalPaketTerakhir',
            'paketMasukTerakhir',
            'menungguPenugasan',
            'sedangDiKirim',
            'perluTindakanRetur',
            'antreanOperasional',
            'kapasitasKurir',
            'codBelumDisetor'
        ));
    }
}
