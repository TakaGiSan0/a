<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user' => ['required'], // Username field
            'password' => ['required'],
        ]);

        // Cari user berdasarkan username terlebih dahulu
        $user = User::where('user', $request->user)->first();

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

        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'super admin') {
            return redirect()->route('superadmin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
