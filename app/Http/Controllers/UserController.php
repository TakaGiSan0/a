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
                'password' => 'required|string',

            ],
            [
                'user.unique' => 'A user with this name already exists.',
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
    public function edit($id)
{

    $user = User::with('pesertaLogin')->findOrFail($id);

    $pesertaAvailableForSelection = Peserta::select('id', 'employee_name', 'badge_no', 'user_id_login')
        ->whereNull('user_id_login')
        ->orWhere('user_id_login', $user->id)
        ->get(); 

    return view('user.edit', [
        'user' => $user,
        'pesertaAvailableForSelection' => $pesertaAvailableForSelection,
    ])->with('hideSidebar', true);
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Temukan user yang akan diupdate

        // Validasi input
        $validatedData = $request->validate(
            [
                'employee_name' => 'required|string|max:255', // Nama peserta wajib diisi
                'user' => 'required', 'string', 'max:255',
                'password' => 'nullable|string',
            ],
            [
                'user.unique' => 'A user with this name already exists.',
            ],
        );

        $newSelectedPeserta = Peserta::where('employee_name', $validatedData['employee_name'])->first();

        // Pastikan peserta yang dipilih valid
        if (!$newSelectedPeserta) {
            return back()->withErrors(['employee_name' => 'Peserta yang dipilih tidak valid.'])->withInput();
        }

        // Ambil peserta yang sebelumnya terkait dengan user ini
        // Menggunakan relasi hasOne dari model User (cek relasi di model User.php)
        $oldPeserta = $user->peserta;

        // Logika untuk melepaskan peserta lama dan mengaitkan peserta baru
        // Jika ada peserta lama DAN id peserta lama berbeda dengan id peserta yang baru dipilih
        if ($oldPeserta && $oldPeserta->id !== $newSelectedPeserta->id) {
            $oldPeserta->user_id_login = null; // Lepaskan user_id_login dari peserta lama
            $oldPeserta->save(); // Simpan perubahan pada peserta lama
        }

        // Kaitkan peserta baru dengan user ini
        $newSelectedPeserta->user_id_login = $user->id;
        $newSelectedPeserta->save(); // Simpan perubahan pada peserta baru

        // Update data user
        $user->user = $validatedData['user']; // Update username user

        // Update password hanya jika ada input password baru
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Ambil role pengguna saat ini (yang sedang login)
        $userRole = auth('')->user()->role;

        // Logika role, sama seperti di fungsi store, untuk membatasi perubahan role
        if ($userRole === 'Admin') {
            // Admin tidak bisa mengubah role user lain dari 'user'.
            // Jika role user yang sedang diedit adalah 'Super Admin', jangan diubah menjadi 'user' oleh Admin.
            if ($user->role !== 'Super Admin') {
                $user->role = 'user';
            }
        } elseif ($userRole === 'Super Admin') {
            // Super Admin bisa mengubah role sesuai input dari form.
            $user->role = $request->input('role', 'user'); // Gunakan 'user' sebagai default jika tidak ada input role
        } else {
            abort(403, 'Unauthorized action.'); // Akses ditolak jika tidak ada role yang sesuai
        }

        $user->save(); // Simpan perubahan pada user

        return redirect()->route('user.index')->with('success', 'User updated successfully.'); // Redirect dengan pesan sukses
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
