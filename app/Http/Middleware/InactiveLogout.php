<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class InactiveLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Cek apakah user sedang login
        if (Auth::check()) {
            $lastActivity = Session::get('lastActivity');

            // Jika ada aktivitas terakhir dan sudah melewati waktu batas
            if ($lastActivity && Carbon::now()->diffInMinutes($lastActivity) >= config('session.lifetime')) {
                Auth::logout();
                Session::forget('lastActivity');
                
                // Redirect ke halaman login dengan pesan
                return redirect('/login')->with('message', 'You have been logged out due to inactivity.');
            }

            // Update waktu aktivitas terakhir
            Session::put('lastActivity', Carbon::now());
        }

        return $next($request);
    }
}
