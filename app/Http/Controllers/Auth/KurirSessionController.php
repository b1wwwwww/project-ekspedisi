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

class KurirSessionController extends Controller
{
    // Menampilkan halaman login terpadu dengan tab kurir aktif.
    public function create(): RedirectResponse
    {
        session()->flash('login_tab', 'kurir');

        return redirect()->route('login');
    }

    // Memproses login Kurir.
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kode_kurir' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $kodeKurir = $request->input('kode_kurir');
        $throttleKey = Str::lower($kodeKurir).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            session()->flash('login_tab', 'kurir');

            throw ValidationException::withMessages([
                'kode_kurir' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.',
            ])->redirectTo(route('login'));
        }

        // Mencari kurir yang aktif berdasarkan kode kurir.
        $kurir = Kurir::with('pengguna')
            ->where('kode_kurir', $kodeKurir)
            ->where('aktif', true)
            ->first();

        $pengguna = $kurir?->pengguna;

        // Memastikan akun benar-benar milik Kurir.
        $berhasil = $pengguna
            && $pengguna->role === 'kurir'
            && Hash::check($request->input('password'), $pengguna->password);

        if (! $berhasil) {
            RateLimiter::hit($throttleKey);
            session()->flash('login_tab', 'kurir');

            throw ValidationException::withMessages([
                'kode_kurir' => 'Kode Kurir atau password salah.',
            ])->redirectTo(route('login'));
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

        session()->flash('login_tab', 'kurir');

        return redirect()->route('login');
    }
}
