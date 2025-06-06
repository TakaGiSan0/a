<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\peserta;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userLogin = auth('web')->user();

        $user = User::select('users.*')->leftJoin('pesertas', 'pesertas.user_id_login', '=', 'users.id')->byUserRole($userLogin)->orderBy('pesertas.employee_name', 'asc')->paginate(10);

        // Pastikan $message selalu terdefinisi
        $message = $user->isEmpty() ? 'No results found for your search.' : '';

        // Kembalikan view dengan data user dan pesan
        return response()->view(
            'user.index',
            [
                'user' => $user,
                'message' => $message,
            ],
            200,
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pesertaTanpaUser = Peserta::whereNull('user_id_login')->get();

        return view('user.create', [
            'pesertaTanpaUser' => $pesertaTanpaUser,
            'user' => auth('')->user(), // Gunakan helper auth() saja
        ])->with('hideSidebar', true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input tanpa 'role'
        $validatedData = $request->validate(
            [
                'employee_name' => 'required|string|max:255',
                'user' => 'required|string|max:255|unique:users,user',
                'password' => 'required|string|min:8',
                
            ],
            [
                'user.unique' => 'User dengan Nama ini sudah ada.',
            ],
        );

        $selectedPeserta = Peserta::where('employee_name', $validatedData['employee_name'])->first();

        // Pastikan peserta ditemukan, meskipun seharusnya selalu karena berasal dari dropdown
        if (!$selectedPeserta) {
            return back()->withErrors(['employe_name' => 'Peserta yang dipilih tidak valid.']);
        }

        $user = new User();

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

        $selectedPeserta->user_id_login = $user->id;
        $selectedPeserta->save();

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
        // Ambil role pengguna saat ini
        $userRole = auth('')->user()->role; 

        
        // Kembalikan view dengan data user
        return view('user.edit', compact('user'))->with('hideSidebar', true);
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
                'dept' => 'required|string|max:255',
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
