<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    // Mengecek apakah user sudah login dan role sesuai dengan yang diberikan
    if (Auth::check() && in_array(Auth::user()->role, $roles)) {
        return $next($request);
    }

    // Jika user tidak memenuhi syarat role, tampilkan pesan unauthorized
    abort(403, 'Unauthorized action.');
}

}
