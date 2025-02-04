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
            'user' => ['required'], // Username field
            'password' => ['required'],
        ]);

        $user = User::whereRaw('BINARY user = ?', [$request->user])->first();
        
        // Jika user tidak ditemukan, kirim pesan error khusus
        if (!$user) {
            return back()
                ->withErrors([
                    'user' => 'Username tidak ditemukan.',
                ])
                ->onlyInput('user');
        }

        // Jika username ditemukan, cek apakah password benar
        if (!Auth::attempt(['user' => $request->user, 'password' => $request->password], $request->filled('remember'))) {
            return back()
            
            ->withErrors([
                'password' => 'Password salah.',
                ])
                ->onlyInput('user');
            }
            
        // Jika autentikasi berhasil, regenerasi session dan redirect
        $request->session()->regenerate();

        // Cek role dan redirect sesuai dengan role user
        $user = Auth::user();
        session(['user_name' => $user->name]);

        if ($user->role == 'Admin' || $user->role == 'Super Admin') {
            return redirect()->route('dashboard.index');
        } elseif ($user->role == 'User') {
            return redirect()->route('dashboard.summary');
        }
        // Jika role tidak ditemukan, kirim pesan error khusus
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
        // Hapus session user_name
        session()->forget('user_name');

        // Logout
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
