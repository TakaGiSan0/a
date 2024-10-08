<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil data user berdasarkan filter pencarian
        $user = User::select('id', 'name', 'user', 'role')->paginate(10);

        // Ambil role pengguna saat ini
        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        // Pilih view berdasarkan role
        switch ($userRole) {
            case 'super admin':
                $view = 'superadmin.user.index';
                break;
            case 'admin':
                $view = 'admin.user.index'; // Ganti dengan view yang sesuai untuk admin
                break;
            default:
                abort(403, 'Unauthorized action.'); // Atau arahkan ke view default atau error
        }

        // Pastikan $message selalu terdefinisi
        $message = $user->isEmpty() ? 'No results found for your search.' : '';

        // Kembalikan view dengan data user dan pesan
        return view($view, [
            'user' => $user,
            'message' => $message,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        // Pilih view berdasarkan role
        switch ($userRole) {
            case 'super admin':
                $view = 'superadmin.user.create';
                break;
            case 'admin':
                $view = 'admin.user.create';
                break;
            default:
                abort(403, 'Unauthorized action.'); // Atau arahkan ke view default atau error
        }

        return view($view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'user' => 'required|string|max:255|unique:users,user',
                'role' => 'required|string|max:255',
                'password' => 'required|string|min:8',
            ],
            [
                'user.unique' => 'User dengan Nama ini sudah ada.',
            ],
        );

        

        $user = new User();

        $user->name = $validatedData['name'];
        $user->user = $validatedData['user'];
        $user->role = $validatedData['role'];
        $user->password = bcrypt($validatedData['password']);

        $user->save();

        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        switch ($userRole) {
            case 'super admin':
                $view = 'superadmin.dashboard';
                break;
            case 'admin':
                $view = 'admin.dashboard';
                break;

            default:
                abort(403, 'Unauthorized action.');
        }
        return redirect()->route($view)->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user $user)
    {
        // Cek otorisasi menggunakan policy
        $this->authorize('update', $user);

        // Ambil role pengguna saat ini
        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        // Pilih view berdasarkan role
        switch ($userRole) {
            case 'super admin':
                $view = 'superadmin.user.edit';
                break;
            case 'admin':
                $view = 'admin.user.edit';
                break;
            default:
                abort(403, 'Unauthorized action.'); // Atau arahkan ke view default atau error
        }

        // Kembalikan view dengan data user
        return view($view, compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        // Validasi input dengan pengecualian untuk ID yang sedang diperbarui
        $validated = $request->validate(
            [
                'user' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'password' => 'nullable|string|max:255',
            ],
            [],
        );

        // Jika password diisi, hash dan simpan, jika tidak, abaikan
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            // Hapus field password agar tidak diupdate jika kosong
            unset($validated['password']);
        }
        // Update data user

        $user->update($validated);

        return redirect()->route('superadmin.user.index')->with('success', 'User berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Menggunakan Policy untuk memeriksa izin
        $this->authorize('delete', $user);

        // Hapus data user
        $user->delete();

        return redirect()->route('superadmin.user.user')->with('success', 'Peserta berhasil dihapus.');
    }
}
