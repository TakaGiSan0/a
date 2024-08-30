<?php

namespace App\Http\Controllers;

use App\Models\peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data peserta tanpa relasi ke tabel lain
        $peserta = Peserta::select('id', 'badge_no', 'employee_name', 'dept', 'position')->get();

        // Kembalikan view dengan data peserta dan pesan
        return view('peserta.index', [
            'peserta' => $peserta,
            'message' => $peserta->isEmpty() ? '' : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('peserta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'badge_no' => 'required|string|max:255',
            'employee_name' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

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
        return view('peserta.edit', compact('peserta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peserta $peserta)
    {
        $validated = $request->validate([
            'badge_no' => 'required|string|max:255',
            'employee_name' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

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
