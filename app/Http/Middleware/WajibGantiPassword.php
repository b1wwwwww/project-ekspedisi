<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WajibGantiPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user(); // Mengambil pengguna yang sedang login.

        if (
            $pengguna &&
            $pengguna->role === 'kurir' && // Memeriksa apakah pengguna adalah Kurir.
            $pengguna->wajib_ganti_password && // Memeriksa apakah Kurir wajib mengganti password.
            ! $request->routeIs(
                'profile.edit',
                'profile.update',
                'password.update',
                'logout'
            )
        ) {
            return redirect()->route('profile.edit') // Mengarahkan Kurir ke halaman Profile.
                ->with('warning', 'Silakan ganti password terlebih dahulu.');
        }

        return $next($request);
    }
}
