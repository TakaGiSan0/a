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
        $training_records = training_record::with(['trainingCategory:id,name', 'peserta'])->get();

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
        $training_records = training_record::with(['trainingCategory:id,name','peserta'])->get();

        return view('index.summary', compact('training_records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = category::all();
        $peserta = peserta::all();

        return view('form.form', compact('categories','peserta'));
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
            'theory_result' => 'required|',
            'practical_result' => 'required|',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|',
            'final_judgement' => 'required|',
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
        $trainingRecord->theory_result = $validateDate['theory_result'];
        $trainingRecord->practical_result = $validateDate['practical_result'];
        $trainingRecord->level = $validateDate['level'];
        $trainingRecord->final_judgement = $validateDate['final_judgement'];
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
    // Ambil data peserta berdasarkan ID
    $peserta = Peserta::find($id);

    if (!$peserta) {
        return response()->json(['error' => 'Peserta not found'], 404);
    }

    // Ambil semua training_record yang terkait dengan peserta tersebut
    $cacheKey = "peserta_records:{$id}";
    $all_records = Cache::remember($cacheKey, 3600, function () use ($id) {
        return training_record::with(['trainingCategory:id,name'])
            ->where('peserta_id', $id)
            ->get();
    });

    // Kelompokkan data berdasarkan category_id
    $grouped_records = $all_records->groupBy('category_id');

    // Jika tidak ada data pelatihan, set grouped_records ke null
    if ($all_records->isEmpty()) {
        $grouped_records = null;
    }

    return response()->json([
        'peserta' => $peserta,
        'grouped_records' => $grouped_records,
    ]);
}


    public function showall($id)
    {
        //
        $trainingRecord = training_record::with(['trainingCategory:id,name','peserta'])->find($id);
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
