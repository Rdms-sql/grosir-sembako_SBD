<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirect setelah login sesuai role
        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();
            if ($user && $user->role === 'konsumen') {
                return '/katalog';
            }
            return '/pemesanan-supplier';
        });

        // Redirect ke login jika belum auth
        $middleware->redirectGuestsTo('/login');

        // Alias middleware role, dipakai di routes/web.php sebagai 'role:admin,kasir' dst.
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );
    })->create();
