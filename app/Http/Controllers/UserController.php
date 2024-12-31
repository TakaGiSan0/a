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

        // Pastikan $message selalu terdefinisi
        $message = $user->isEmpty() ? 'No results found for your search.' : '';

        // Kembalikan view dengan data user dan pesan
        return response()->view('user.index', [
            'user' => $user,
            'message' => $message,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        // Pilih view berdasarkan role
        return response()->view('user.create', [], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input tanpa 'role'
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'user' => 'required|string|max:255|unique:users,user',
                'password' => 'required|string|min:8',
            ],
            [
                'user.unique' => 'User dengan Nama ini sudah ada.',
            ],
        );

        $user = new User();

        $user->name = $validatedData['name'];
        $user->user = $validatedData['user'];

        // Ambil role pengguna saat ini
        $userRole = auth('')->user()->role;

        // Jika role pengguna adalah admin, set role user baru menjadi 'user'
        if ($userRole === 'Admin') {
            $user->role = 'user';
        } elseif ($userRole === 'Super Admin') {
            // Jika bukan admin, set role sesuai kebutuhan (misalnya dari input atau default)
            $user->role = $request->input('role'); // Ganti 'default_role' dengan nilai default yang diinginkan
        } else {
            abort(403, 'Unauthorized action.');
        }

        $user->password = bcrypt($validatedData['password']);

        $user->save();

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implementasi fungsi show jika diperlukan
        return response()->json([], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Cek otorisasi menggunakan policy
        $this->authorize('update', $user);

        // Ambil role pengguna saat ini
        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        // Kembalikan view dengan data user
        return response()->view('user.edit', compact('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
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

        return redirect()->route('user.index')->with('success', 'User successfully updated.');
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

        return redirect()->route('user.index')->with('success', 'User successfully deleted.');
    }
}
