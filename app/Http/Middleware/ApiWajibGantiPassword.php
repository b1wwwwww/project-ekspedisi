<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiWajibGantiPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user();

        if (
            $pengguna &&
            $pengguna->role === 'kurir' &&
            $pengguna->wajib_ganti_password &&
            ! $request->routeIs('api.logout', 'api.ganti-password')
        ) {
            return response()->json([
                'message' => 'Silakan ganti password terlebih dahulu.',
                'wajib_ganti_password' => true,
            ], 423);
        }

        return $next($request);
    }
}