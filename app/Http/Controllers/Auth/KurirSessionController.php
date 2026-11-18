<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KurirSessionController extends Controller
{
    // Menampilkan halaman login Kurir.
    public function create(): View
    {
        return view('auth.kurir-login');
    }

    // Memproses login Kurir.
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kurir' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($validated['kode_kurir'])
            .'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'kode_kurir' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.',
            ]);
        }

        // Mencari kurir yang aktif berdasarkan kode kurir.
        $kurir = Kurir::with('pengguna')
            ->where('kode_kurir', $validated['kode_kurir'])
            ->where('aktif', true)
            ->first();

        $pengguna = $kurir?->pengguna;

        // Memastikan akun benar-benar milik Kurir.
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

        Auth::login($pengguna, $request->boolean('remember'));

        $request->session()->regenerate();

        // Kurir wajib mengganti password saat login pertama.
        if ($pengguna->wajib_ganti_password) {
            return redirect()->route('profile.edit');
        }

        return redirect()->route('kurir.penugasan.index');
    }

    // Logout Kurir.
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('kurir.login');
    }
}
