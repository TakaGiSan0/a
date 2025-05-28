<?php

namespace App\Http\Controllers;

use App\Models\TrainingEvaluation;
use Illuminate\Http\Request;
use App\Models\Hasil_Peserta;
use App\Models\TrainingRecord;

class TrainingEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('web')->user();

        // Ambil nilai pencarian dari request
        $searchTerm = $request->input('search'); // 'search' adalah nama parameter di URL, cth: ?search=John

        // Mulai query untuk TrainingEvaluation
        $query = TrainingEvaluation::with('hasilPeserta.pesertas', 'hasilPeserta.trainingRecord');

        // Jika Super Admin → Lihat semua evaluasi
        if ($user->role === 'Super Admin') {
            // Tambahkan kondisi pencarian jika ada searchTerm
            if ($searchTerm) {
                $query->whereHas('hasilPeserta.pesertas', function ($q) use ($searchTerm) {
                    $q->where('employee_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('badge_no', 'like', '%' . $searchTerm . '%');
                });
            }
            $trainingEvaluations = $query->get(); // Eksekusi query
        } else {
            // Ambil ID peserta milik user ini (asumsi: relasi user ke peserta)
            $pesertaId = optional($user->pesertaLogin)->id;

            // Pastikan pesertaId ada
            if ($pesertaId) {
                // Hanya ambil evaluasi milik peserta terkait
                $query->whereHas('hasilPeserta', function ($q) use ($pesertaId) {
                    $q->where('peserta_id', $pesertaId);
                });

                
            } else {
                // Jika tidak ada pesertaId, tidak ada evaluasi yang harus ditampilkan
                $query->whereRaw('1 = 0'); // Query yang selalu false
            }

            $trainingEvaluations = $query->get(); // Eksekusi query
        }

        return view('evaluation.index', compact('trainingEvaluations'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question_1' => 'required|string',
            'question_2' => 'required|string',
            'question_3' => 'required|string',
            'question_4' => 'required|string',
            'question_5' => 'required|string',

        ]);

        $evaluation = TrainingEvaluation::findOrFail($id);
        $evaluation->update($request->only([
            'question_1',
            'question_2',
            'question_3',
            'question_4',
            'question_5',
            'status',
        ]));

        return redirect()->back()->with('success', 'Evaluation updated successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(TrainingEvaluation $trainingEvaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluation = TrainingEvaluation::findOrFail($id);

        return response()->json([
            'question_1' => $evaluation->question_1,
            'question_2' => $evaluation->question_2,
            'question_3' => $evaluation->question_3,
            'question_4' => $evaluation->question_4,
            'question_5' => $evaluation->question_5,
            'status' => $evaluation->status,

        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingEvaluation $trainingEvaluation)
    {
        //
    }
}
