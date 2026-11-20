<?php

namespace App\Http\Controllers\Api\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login kurir dari APK, mengembalikan token Bearer (bukan session/cookie).
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_kurir' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($validated['kode_kurir']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'kode_kurir' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.',
            ]);
        }

        $kurir = Kurir::with('pengguna')
            ->where('kode_kurir', $validated['kode_kurir'])
            ->where('aktif', true)
            ->first();

        $pengguna = $kurir?->pengguna;

        $berhasil = $pengguna
            && $pengguna->role === 'kurir'
            && Hash::check($validated['password'], $pengguna->password);

        if (! $berhasil) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'kode_kurir' => 'Kode Kurir atau password salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $pengguna->tokens()->delete();

        $token = $pengguna->createToken('apk-kurir')->plainTextToken;

        return response()->json([
            'token' => $token,
            'wajib_ganti_password' => (bool) $pengguna->wajib_ganti_password,
            'kurir' => [
                'id' => $kurir->id,
                'kode_kurir' => $kurir->kode_kurir,
                'nama' => $pengguna->nama,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout.']);
    }

    public function gantiPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $pengguna = $request->user();

        if (! Hash::check($validated['password_lama'], $pengguna->password)) {
            throw ValidationException::withMessages([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        $pengguna->update([
            'password' => Hash::make($validated['password_baru']),
            'wajib_ganti_password' => false,
        ]);

        return response()->json(['message' => 'Password berhasil diganti.']);
    }
}