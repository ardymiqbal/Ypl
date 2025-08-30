<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOr404
{
    /**
     * Hanya izinkan akses jika guard 'admin' terautentikasi.
     * Jika tidak, tampilkan 404.
     */
    public function handle(Request $request, Closure $next, string $guard = 'admin')
    {
        if (! Auth::guard($guard)->check()) {
            abort(404);
        }

        // Pastikan gate/policy pakai guard ini selama request
        Auth::shouldUse($guard);

        return $next($request);
    }
}
