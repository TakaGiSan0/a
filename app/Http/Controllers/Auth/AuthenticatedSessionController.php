<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user' => ['required'],  // Username field
            'password' => ['required'],
        ]);

        // Modifikasi Auth attempt untuk menggunakan user
        if (!Auth::attempt(['user' => $request->user, 'password' => $request->password], $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'user' => __('auth.failed'),
            ]);
        }
        $request->session()->regenerate();

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
