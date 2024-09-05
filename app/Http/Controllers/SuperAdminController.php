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
        $training_records = training_record::with(['trainingCategory:id,name'])->get();

        return view('index.summary', compact('training_records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = category::all();
        $peserta = peserta::all();

        return view('form.form', compact('categories', 'peserta'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{

    // Ambil event number terakhir dari database dan tambahkan 1
    $lastEventNumber = Training_Record::max('event_number') ?? 0;
    $newEventNumber = $lastEventNumber + 1;

    $data = $request->all();

    // Simpan data pelatihan utama
    $trainingRecord = Training_Record::create([
        'training_name' => $data['training_name'],
        'doc_ref' => $data['doc_ref'],
        'job_skill' => $data['job_skill'],
        'trainer_name' => $data['trainer_name'],
        'rev' => $data['rev'],
        'station' => $data['station'],
        'training_date' => $data['training_date'],
        'skill_code' => $data['skill_code'],
        'category_id' => $data['category_id'],
        'event_number' => $newEventNumber,
        // Tambahkan detail lainnya jika perlu
    ]);

    foreach ($data['participants'] as $participant) {
        // Ambil data peserta berdasarkan badge number
        $peserta = Peserta::where('badge_no', $participant['badge_no'])->first();

        // Pastikan peserta ditemukan
        if ($peserta) {
            // Buat relasi di tabel pivot training_record_peserta
            $trainingRecord->pesertas()->attach($peserta->id, [
                'level' => $participant['level'],
                'final_judgement' => $participant['final_judgement'],
                'license' => $participant['license'],
                'theory_result' => $participant['theory_result'],
                'practical_result' => $participant['practical_result'],
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

    public function showall($event_number)
    {
// Ambil semua training records berdasarkan event_number
$trainingRecords = Training_Record::with(['pesertas', 'trainingCategory'])
        ->where('event_number', $event_number)
        ->get();

    if ($trainingRecords->isEmpty()) {
        return response()->json(['error' => 'No records found'], 404);
    }

    // Ambil data peserta untuk setiap training record
    $trainingWithPeserta = $trainingRecords->map(function ($record) {
        // Ambil semua peserta untuk training record tertentu
        $peserta = $record->pesertas()->get();

        return [
            'id' => $record->id,
            'doc_ref' => $record->doc_ref,
            'license' => $record->license,
            'training_name' => $record->training_name,
            'job_skill' => $record->job_skill,
            'trainer_name' => $record->trainer_name,
            'rev' => $record->rev,
            'station' => $record->station,
            'skill_code' => $record->skill_code,
            'training_date' => $record->training_date,
            'event_number' => $record->event_number,
            'peserta' => $peserta
        ];
    });

    return response()->json($trainingWithPeserta)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

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
