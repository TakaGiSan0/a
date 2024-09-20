<?php

namespace App\Http\Controllers;

use App\Models\peserta;
use Illuminate\Http\Request;


class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('badge_no', '');

        // Mulai dengan query peserta
        $query = Peserta::query();

        // Terapkan filter pencarian jika ada
        if (!empty($searchQuery)) {
            $query->where('badge_no', 'like', '%' . $searchQuery . '%');
        }

        // Ambil data peserta berdasarkan filter pencarian
        $peserta = $query->select('id', 'badge_no', 'employee_name', 'dept', 'position')->paginate(10);

        $userRole = auth('')->user()->role; // Asumsikan 'role' adalah atribut di tabel users

        // Pilih view berdasarkan role
        switch ($userRole) {
            case 'super admin':
                $view = 'superadmin.peserta.index';
                break;
            case 'admin':
                $view = 'admin.peserta.index'; // Ganti dengan view yang sesuai untuk admin
                break;
            default:
                abort(403, 'Unauthorized action.'); // Atau arahkan ke view default atau error
        }
        // Kembalikan view dengan data peserta dan pesan
        return view($view, [
            'peserta' => $peserta,
            'searchQuery' => $searchQuery, // Kirimkan pencarian ke view untuk mempertahankan nilai pencarian
            'message' => $peserta->isEmpty() ? 'No results found for your search.' : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.peserta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate(
            [
                'badge_no' => 'required|string|max:255|regex:/^[A-Z0-9\-]+$/|unique:pesertas,badge_no',
                'employee_name' => 'required|string|max:255|regex:/^[a-zA-Z\- ]+$/|unique:pesertas,employee_name',
                'dept' => 'required|string|max:255',
                'position' => 'required|string|max:255',
            ],
            [
                'employee_name.unique' => 'Peserta dengan Nama ini sudah ada.',
                'badge_no.unique' => 'Peserta dengan Badge No ini sudah ada.',
                'badge_no.regex' => 'Badge No hanya boleh berisi huruf besar, angka, dan tanda hubung.',
                'employee_name.regex' => 'Nama hanya boleh berisi huruf',
            ],
        );

        // Membuat instance Peserta baru
        $peserta = new peserta();

        // Mengisi data peserta dengan input yang divalidasi
        $peserta->badge_no = $validatedData['badge_no'];
        $peserta->employee_name = $validatedData['employee_name'];
        $peserta->dept = $validatedData['dept'];
        $peserta->position = $validatedData['position'];

        // Simpan data ke database
        $peserta->save();

        // Mengembalikan response atau redirect
        return redirect()->route('superadmin.peserta')->with('success', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peserta $peserta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peserta $peserta)
    {
        // Cek otorisasi menggunakan policy
        $this->authorize('update', $peserta);

        // Kembalikan view dengan data peserta
        return view('superadmin.peserta.edit', compact('peserta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peserta $peserta)
    {
        // Validasi input dengan pengecualian untuk ID yang sedang diperbarui
        $validated = $request->validate(
            [
                'badge_no' => 'required|string|max:255|regex:/^[A-Z0-9\-]+$/|unique:pesertas,badge_no,' . $peserta->id,
                'employee_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:pesertas,employee_name,' . $peserta->id,
                'dept' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'position' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            ],
            [
                'badge_no.regex' => 'Badge No hanya boleh berisi huruf besar, angka, dan tanda hubung.',
                'employee_name.regex' => 'Nama hanya boleh berisi huruf',
                'badge_no.unique' => 'Peserta dengan Badge No ini sudah ada.',
                'employee_name.unique' => 'Peserta dengan Nama ini sudah ada.',
            ],
        );

        // Update data peserta
        $peserta->update($validated);

        return redirect()->route('superadmin.peserta')->with('success', 'Peserta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);

        $peserta->trainingRecords()->delete();

        // Menggunakan Policy untuk memeriksa izin
        $this->authorize('delete', $peserta);

        // Hapus data peserta
        $peserta->delete();

        // Redirect atau response dengan pesan sukses
        return redirect()->route('superadmin.peserta')->with('success', 'Peserta berhasil dihapus.');
    }

    public function getParticipantByBadgeNo($badge_no)
    {
        $participant = peserta::where('badge_no', $badge_no)->first();

        if ($participant) {
            return response()->json($participant);
        } else {
            return response()->json(['error' => 'Participant not found'], 404);
        }
    }
}
