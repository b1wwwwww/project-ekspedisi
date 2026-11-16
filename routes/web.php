<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PaketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // List Daftar Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])
        ->name('pelanggan.index');

        // List Daftar Paket
    Route::get('/paket', [PaketController::class, 'index'])
        ->name('paket.index');

        // Form Tambah Paket
    Route::get('/paket/create', [PaketController::class, 'create'])
        ->name('paket.create');

        // Menyimpan data paket baru
    Route::post('/paket', [PaketController::class, 'store'])
        ->name('paket.store');

        // Menghitung tarif paket berdasarkan berat dan zona
    Route::get('/paket/hitung-tarif', [PaketController::class, 'hitungTarif'])
        ->name('paket.hitung-tarif');
});

require __DIR__.'/auth.php';