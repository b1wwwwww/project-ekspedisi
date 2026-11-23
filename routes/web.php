<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImportDataController;
use App\Http\Controllers\Admin\KurirController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PenugasanKurirController;
use App\Http\Controllers\Admin\SetoranCodController;
use App\Http\Controllers\Kurir\PenugasanController;
use App\Http\Controllers\Manajer\DashboardController as ManajerDashboardController;
use App\Http\Controllers\Manajer\LaporanController; // Alias untuk menghindari konflik nama dengan DashboardController Admin
use App\Http\Controllers\Manajer\TarifController;
use App\Http\Controllers\PelacakanResiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [PelacakanResiController::class, 'index'])->name('pelacakan-resi.index');

Route::get('/dashboard', function (Request $request) {
    $role = $request->user()->role;

    if ($role === 'kurir') {
        return redirect()->route('kurir.penugasan.index');
    }

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'manajer') {
        return redirect()->route('manajer.dashboard');
    }

    abort(403);
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'wajib.ganti.password'])->group(function () {

    // Profile dapat diakses oleh Admin, Manajer, dan Kurir.
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Khusus Manajer: dashboard
    Route::middleware('role:manajer')->group(function () {

        Route::get('/manajer', [ManajerDashboardController::class, 'index'])
            ->name('manajer.dashboard');

        Route::put('/manajer/tarif/{zonaTarif}', [TarifController::class, 'update'])
            ->name('manajer.tarif.update');

        // Laporan Manajer (read-only).
        Route::get('/manajer/laporan/pengiriman', [LaporanController::class, 'pengiriman'])
            ->name('manajer.laporan.pengiriman');

        Route::get('/manajer/laporan/pendapatan-zona', [LaporanController::class, 'pendapatanZona'])
            ->name('manajer.laporan.pendapatan-zona');

        Route::get('/manajer/laporan/rekap-cod', [LaporanController::class, 'rekapCod'])
            ->name('manajer.laporan.rekap-cod');

    });

    // Halaman khusus Kurir.
    Route::middleware('role:kurir')->group(function () {

        Route::get('/kurir/penugasan', [PenugasanController::class, 'index'])
            ->name('kurir.penugasan.index');

        Route::get('/kurir/penugasan/{id}', [PenugasanController::class, 'show'])
            ->name('kurir.penugasan.show');

        Route::post('/kurir/penugasan/{id}/status', [PenugasanController::class, 'updateStatus'])
            ->name('kurir.penugasan.update-status');

    });

    // Halaman khusus Admin dan Manajer.
    Route::middleware('role:admin,manajer')->group(function () {

        // Data Pelanggan: dapat dilihat oleh Admin dan Manajer.
        Route::get('/pelanggan', [PelangganController::class, 'index'])
            ->name('pelanggan.index');

        // Data Kurir: dapat dilihat oleh Admin dan Manajer.
        Route::get('/admin/kurir', [KurirController::class, 'index'])
            ->name('admin.kurir.index');

        // Daftar tarif dapat dilihat Admin dan Manajer.
        Route::get('/tarif', [TarifController::class, 'index'])
            ->name('tarif.index');

    });

    // Khusus Admin: tambah dan ubah data Pelanggan.
    Route::middleware('role:admin')->group(function () {

        Route::get('/pelanggan/create', [PelangganController::class, 'create'])
            ->name('pelanggan.create');

        Route::post('/pelanggan', [PelangganController::class, 'store'])
            ->name('pelanggan.store');

        // Edit data pelanggan
        Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])
            ->name('pelanggan.edit');

        // Simpan perubahan data pelanggan
        Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])
            ->name('pelanggan.update');

    });

    // Khusus Admin: pengelolaan data Kurir dan operasional pengiriman.
    Route::middleware('role:admin')->group(function () {

        // Dashboard Admin
        Route::get('/admin', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Data Kurir
        Route::get('/admin/kurir/create', [KurirController::class, 'create'])
            ->name('admin.kurir.create');

        Route::post('/admin/kurir', [KurirController::class, 'store'])
            ->name('admin.kurir.store');

        Route::get('/admin/kurir/{kurir}/edit', [KurirController::class, 'edit'])
            ->name('admin.kurir.edit');

        Route::put('/admin/kurir/{kurir}', [KurirController::class, 'update'])
            ->name('admin.kurir.update');

        Route::patch('/admin/kurir/{kurir}/status', [KurirController::class, 'toggleStatus'])
            ->name('admin.kurir.toggle-status');

        // Impor data awal pelanggan, kurir, dan paket historis secara berurutan.
        Route::get('/admin/import', [ImportDataController::class, 'index'])
            ->name('admin.import.index');

        Route::post('/admin/import', [ImportDataController::class, 'store'])
            ->name('admin.import.store');

        // Penugasan Kurir
        Route::get('/admin/penugasan-kurir', [PenugasanKurirController::class, 'index'])
            ->name('admin.penugasan-kurir.index');

        Route::post('/admin/penugasan-kurir', [PenugasanKurirController::class, 'store'])
            ->name('admin.penugasan-kurir.store');

        // Operasional Paket
        Route::get('/paket', [PaketController::class, 'index'])
            ->name('paket.index');

        Route::get('/paket/create', [PaketController::class, 'create'])
            ->name('paket.create');

        Route::get('/paket/hitung-tarif', [PaketController::class, 'hitungTarif'])
            ->name('paket.hitung-tarif');

        Route::get('/paket/{paket}', [PaketController::class, 'show'])
            ->name('paket.show');

        Route::post('/paket', [PaketController::class, 'store'])
            ->name('paket.store');

        Route::patch('/paket/{paket}/transit', [PaketController::class, 'ubahKeTransit'])
            ->name('paket.transit');

        Route::patch('/paket/{paket}/retur', [PaketController::class, 'prosesRetur'])
            ->name('paket.retur');

        Route::get('/admin/setoran-cod', [SetoranCodController::class, 'index'])
            ->name('admin.setoran-cod.index');

        Route::post('/admin/setoran-cod', [SetoranCodController::class, 'store'])
            ->name('admin.setoran-cod.store');

    });

});

require __DIR__.'/auth.php';
