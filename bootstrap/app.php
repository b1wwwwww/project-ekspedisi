<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\WajibGantiPassword;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Menambahkan middleware wajib ganti password untuk Kurir.
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'wajib.ganti.password' => WajibGantiPassword::class,
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request): string {
            $pengguna = $request->user();

            if ($pengguna?->role === 'kurir') {
                return route('kurir.penugasan.index');
            }

            return route('admin.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
