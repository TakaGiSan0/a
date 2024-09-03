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
    dd($request->all());
    $data = $request->all();

    // Ambil detail training yang sama untuk setiap peserta
    $trainingName = $data['training_name'];
    $docRef = $data['doc_ref'];
    $job_skill = $data['job_skill'];
    $trainer_name = $data['trainer_name'];
    $rev = $data['rev'];
    $station = $data['station'];
    $training_date = $data['training_date'];
    $skill_code = $data['skill_code'];
    $category_id = $data['category_id'];
    // Ambil detail lain jika diperlukan

    foreach ($data['participants'] as $participant) {
        $peserta = Peserta::where('badge_no', $participant['badge_no'])->first();

        // Pastikan peserta ditemukan
        if ($peserta) {
            training_record::create([
                'training_name' => $trainingName,
                'doc_ref' => $docRef,
                // Isi detail lainnya jika perlu
                'job_skill' => $job_skill,
                'trainer_name' => $trainer_name,
                'rev' => $rev,
                'station' => $station,
                'skill_code' => $skill_code,
                'training_date' => $training_date,
                'level' => $participant['level'],
                'final_judgement' => $participant['final_judgement'],
                'category_id' => $category_id,
                'license' => $participant['license'],
                'theory_result' => $participant['theory_result'],
                'practical_result' => $participant['practical_result'],
                'peserta_id' => $peserta->id
            ]);
        }
    }

    return redirect()->back()->with('success', 'Training records created successfully!');
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
