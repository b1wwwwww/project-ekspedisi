<?php

use App\Http\Controllers\Api\Kurir\AuthController;
use App\Http\Controllers\Api\Kurir\CodController;
use App\Http\Controllers\Api\Kurir\PenugasanController;
use Illuminate\Support\Facades\Route;

// Login tidak pakai middleware auth karena di sinilah token pertama kali dibuat.
Route::post('/login', [AuthController::class, 'login']);

// Semua endpoint di bawah wajib token (Sanctum) DAN role kurir.
Route::middleware(['auth:sanctum', 'role:kurir', 'api.wajib.ganti.password'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::post('/ganti-password', [AuthController::class, 'gantiPassword'])->name('api.ganti-password');

    Route::get('/paket', [PenugasanController::class, 'index']);
    Route::get('/paket/{id}', [PenugasanController::class, 'show']);
    Route::patch('/paket/{id}/status', [PenugasanController::class, 'updateStatus']);

    Route::get('/cod/belum-disetor', [CodController::class, 'belumDisetor']);
});