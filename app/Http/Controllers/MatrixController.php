<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hasil_peserta;

class MatrixController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('web')->user();
        $searchQuery = $request->input('searchQuery'); // Ambil input pencarian

        $matrix = Hasil_Peserta::where('license', 1)
            ->byUserRole($user)
            ->whereHas('trainingrecord', function ($query) {
                $query->where('status', 'completed');
            })
            ->whereHas('pesertas', function ($query) use ($searchQuery) { // Gunakan whereHas ke peserta
                $query->where('employee_name', 'like', "%$searchQuery%")
                    ->orWhere('badge_no', 'like', "%$searchQuery%");
            })
            ->with(['trainingrecord.user', 'pesertas']) // Ambil relasi peserta juga
            ->get();

        return view('content.matrix', compact('matrix', 'searchQuery'));
    }




    public function show($id)
    {
        $matrix = hasil_peserta::select('certificate', 'expired_date', 'category')->where('id', $id)->first();

        if (!$matrix) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return response()->json([
            'id' => $matrix->id,
            'certificate' => $matrix->certificate,
            'expired_date' => $matrix->expired_date,
            'category' => $matrix->category
        ]);
    }


    public function updateLicense(request $request, $id)
    {
        $validated = $request->validate([
            'certificate' => 'required|string|max:255',
            'expired_date' => 'required|date|max:255',
            'category' => 'required|string|max:255',
        ]);

        $matrix = hasil_peserta::findOrFail($id);
        $matrix->certificate = $validated['certificate'];
        $matrix->expired_date = $validated['expired_date'];
        $matrix->category = $validated['category'];

        $matrix->save();

        return redirect()->back()->with('success', 'Matrix Succesfully Updated.');
    }
}
