<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\PenugasanKurirController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    // Dashboard Admin
    Route::get('/admin', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // List Daftar Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])
        ->name('pelanggan.index');

   

        // Halaman Penugasan Kurir
    Route::get('/admin/penugasan-kurir', [PenugasanKurirController::class, 'index'])
        ->name('admin.penugasan-kurir.index');

        // Menyimpan penugasan kurir untuk paket
    Route::post('/admin/penugasan-kurir', [PenugasanKurirController::class, 'store'])
        ->name('admin.penugasan-kurir.store');


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
        // Mengubah status paket menjadi "Transit"
        // pake patch hanya memperbarui sebagian data paket, yaitu statusnya.
    Route::patch('/paket/{paket}/transit', [PaketController::class, 'ubahKeTransit'])
        ->name('paket.transit'); 


    // Tambah Pelanggan
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])
        ->name('pelanggan.create');

    Route::post('/pelanggan', [PelangganController::class, 'store'])
        ->name('pelanggan.store');
});

require __DIR__.'/auth.php';