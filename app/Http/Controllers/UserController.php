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
        $searchQuery = $request->input('badge_no', '');

        // Mulai dengan query peserta
        $query = User::query();

        // Terapkan filter pencarian jika ada
        if (!empty($searchQuery)) {
            $query->where('badge_no', 'like', '%' . $searchQuery . '%');
        }

        // Ambil data user berdasarkan filter pencarian
        $user = $query->select('id', 'name', 'user', 'role')->paginate(10);

        // Kembalikan view dengan data user dan pesan
        return view('peserta.user.user', [
            'user' => $user,
            'searchQuery' => $searchQuery, // Kirimkan pencarian ke view untuk mempertahankan nilai pencarian
            'message' => $user->isEmpty() ? 'No results found for your search.' : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('peserta.user.create');
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

        return redirect()->route('superadmin.user.index')->with('success', 'Peserta berhasil ditambahkan.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
