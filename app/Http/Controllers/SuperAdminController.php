<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\training_record;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\final_judgement;
use App\Models\practical_result;
use App\Models\theory_result;
use App\Models\level;
use App\Models\peserta;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $training_records = training_record::with(['trainingCategory:id,name', 'final_judgement:id,name', 'level:id,level', 'peserta'])->get();

        return view('user.super_admin', compact('training_records'));
    }

    public function employee(Request $request)
    {
        // Ambil filter dan input pencarian dari request
        $deptFilter = $request->input('dept', []); // Pastikan deptFilter adalah array
        $searchQuery = $request->input('badge_no', '');

        // Pastikan deptFilter adalah array
        if (is_string($deptFilter)) {
            $deptFilter = explode(',', $deptFilter); // Ubah string menjadi array jika perlu
        }
        // Dapatkan semua nilai dept unik dari tabel peserta
        $uniqueDepts = Peserta::select('dept')->distinct()->pluck('dept')->toArray(); // Konversi ke array

        // Mulai dengan query peserta
        $query = Peserta::query();

        // Terapkan filter berdasarkan dept jika ada
        if (!empty($deptFilter) && is_array($deptFilter)) {
            // Pastikan deptFilter adalah array
            $query->whereIn('dept', $deptFilter);
        }

        // Terapkan filter pencarian jika ada
        if (!empty($searchQuery)) {
            $query->where('badge_no', 'like', '%' . $searchQuery . '%');
        }

        // Ambil data peserta dengan filter
        $peserta_records = $query->get();

        // Buat koleksi baru dengan data peserta
        $paginatedPesertaRecords = new \Illuminate\Pagination\LengthAwarePaginator(
            $peserta_records->forPage($request->input('page', 1), 10), // Paginasi
            $peserta_records->count(), // Total item
            10, // Item per halaman
            $request->input('page', 1), // Halaman saat ini
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('index.employee', [
            'peserta_records' => $paginatedPesertaRecords,
            'deptFilter' => $deptFilter, // Kirimkan filter ke view untuk mempertahankan nilai filter
            'searchQuery' => $searchQuery, // Kirimkan pencarian ke view untuk mempertahankan nilai pencarian
            'uniqueDepts' => $uniqueDepts, // Kirimkan nilai dept unik ke view
        ]);
    }

    public function summary()
    {
        $training_records = training_record::with(['trainingCategory:id,name', 'final_judgement:id,name', 'level:id,level', 'peserta', 'practical:id,name', 'theory:id,name'])->get();

        return view('index.summary', compact('training_records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = category::all();
        $final_judgement = final_judgement::all();
        $level = level::all();
        $practical_result = practical_result::all();
        $theory_result = theory_result::all();
        $peserta = peserta::all();

        return view('form.form', compact('categories', 'theory_result', 'level', 'practical_result', 'final_judgement', 'peserta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Received data:', $request->all());

        $validateDate = $request->validate([
            'training_name' => 'required|string|max:255',
            'doc_ref' => 'required',
            'job_skill' => 'required',
            'trainer_name' => 'required',
            'rev' => 'required',
            'license' => 'nullable|boolean',
            'station' => 'required',
            'skill_code' => 'required',
            'training_date' => 'required',
            'peserta_id' => 'required|exists:pesertas,id',
            'theory_result_id' => 'required|exists:theory_results,id',
            'practical_result_id' => 'required|exists:practical_results,id',
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'final_judgement_id' => 'required|exists:final_judgements,id',
        ]);

        $validatedDate['license'] = $request->has('license') ? 1 : 0;

        $trainingRecord = new training_record();
        $trainingRecord->training_name = $validateDate['training_name'];
        $trainingRecord->doc_ref = $validateDate['doc_ref'];
        $trainingRecord->job_skill = $validateDate['job_skill'];
        $trainingRecord->trainer_name = $validateDate['trainer_name'];
        $trainingRecord->rev = $validateDate['rev'];
        $trainingRecord->station = $validateDate['station'];
        $trainingRecord->skill_code = $validateDate['skill_code'];
        $trainingRecord->training_date = $validateDate['training_date'];
        $trainingRecord->peserta_id = $validateDate['peserta_id'];
        $trainingRecord->theory_result_id = $validateDate['theory_result_id'];
        $trainingRecord->practical_result_id = $validateDate['practical_result_id'];
        $trainingRecord->level_id = $validateDate['level_id'];
        $trainingRecord->final_judgement_id = $validateDate['final_judgement_id'];
        $trainingRecord->category_id = $validateDate['category_id'];
        $trainingRecord->license = $validatedDate['license'];

        $trainingRecord->save();

        return redirect()->route('superadmin.dashboard')->with('success', 'Training record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil data training_record berdasarkan ID
        $training_record = training_record::find($id);

        if (!$training_record) {
            return response()->json(['error' => 'Training record not found'], 404);
        }

        $peserta_id = $training_record->peserta_id;

        // Cek apakah data sudah ada di cache
        $cacheKey = "peserta_records:{$peserta_id}";
        $all_records = Cache::remember($cacheKey, 3600, function () use ($peserta_id) {
            return training_record::with(['trainingCategory:id,name', 'final_judgement:id,name', 'level:id,level', 'peserta'])
                ->where('peserta_id', $peserta_id)
                ->get();
        });

        // Kelompokkan data berdasarkan category_id
        $grouped_records = $all_records->groupBy('category_id');

        // Jika tidak ada data pelatihan, set grouped_records ke null
        if ($all_records->isEmpty()) {
            $grouped_records = null;
        }

        return response()->json([
            'peserta' => $training_record->peserta,
            'grouped_records' => $grouped_records,
        ]);
    }

    public function showall($id)
    {
        //
        $trainingRecord = training_record::with(['trainingCategory:id,name', 'final_judgement:id,name', 'level:id,level', 'peserta', 'practical:id,name', 'theory:id,name'])->find($id);
        if (!$trainingRecord) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json($trainingRecord);
        return response($trainingRecord)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Escape $escape)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Escape $escape)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Escape $escape)
    {
        //
    }
}
