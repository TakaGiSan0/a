<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user' => ['required'],
            'password' => ['required'],
        ]);

        
        $user = User::whereRaw('BINARY user = ?', [$request->user])->first();
        
        if (!$user) {
            return back()
                ->withErrors([
                    'user' => 'Username Not Found.',
                ])
                ->onlyInput('user');
        }

     
        if (!Auth::attempt(['user' => $request->user, 'password' => $request->password], $request->filled('remember'))) {
            return back()
            
            ->withErrors([
                'password' => 'Password Invalid',
                ])
                ->onlyInput('user');
            }
            
       
        $request->session()->regenerate();

        
        $user = Auth::user();
        session(['user_name' => $user->name]);

        if ($user->role == 'Admin' || $user->role == 'Super Admin') {
            return redirect()->route('dashboard.index');
        } elseif ($user->role == 'User') {
            return redirect()->route('dashboard.peserta');
        }
        
        if (!$user) {
            return back()
                ->withErrors([
                    'user' => 'Role user tidak ditemukan.',
                ])
                ->onlyInput('user');
        }
    }

    public function destroy(Request $request)
{
    // Lakukan logout pada guard yang aktif
    Auth::logout();

    // Hancurkan sesi lama untuk keamanan
    $request->session()->invalidate();

    // Buat ulang token CSRF untuk sesi baru
    $request->session()->regenerateToken();

    // Arahkan ke halaman login
    return redirect('/login');
}
}
