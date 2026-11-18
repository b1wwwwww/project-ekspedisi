<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KurirController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PenugasanKurirController;
use App\Http\Controllers\Kurir\PenugasanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    if ($request->user()->role === 'kurir') {
        return redirect()->route('kurir.penugasan.index');
    }

    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'wajib.ganti.password'])->group(function () {

    // Profile dapat diakses oleh Admin, Manajer, dan Kurir.
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

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

        // Dashboard Admin
        Route::get('/admin', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Data Pelanggan
        Route::get('/pelanggan', [PelangganController::class, 'index'])
            ->name('pelanggan.index');

        Route::get('/pelanggan/create', [PelangganController::class, 'create'])
            ->name('pelanggan.create');

        Route::post('/pelanggan', [PelangganController::class, 'store'])
            ->name('pelanggan.store');

        // Penugasan Kurir
        Route::get('/admin/penugasan-kurir', [PenugasanKurirController::class, 'index'])
            ->name('admin.penugasan-kurir.index');

        Route::post('/admin/penugasan-kurir', [PenugasanKurirController::class, 'store'])
            ->name('admin.penugasan-kurir.store');

        // Data Paket
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

        // Kelola akun dan profil Kurir.
        Route::get('/admin/kurir', [KurirController::class, 'index'])
            ->name('admin.kurir.index');

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

    });

});

require __DIR__.'/auth.php';
