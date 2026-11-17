<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi route berdasarkan role pengguna.
 * Pemakaian di route: ->middleware('role:panitia')
 *
 * Middleware ini dipasang SETELAH middleware 'auth', jadi pengguna
 * yang belum login sudah dialihkan ke halaman login lebih dulu.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== $role) {
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}