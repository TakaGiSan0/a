<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use Illuminate\Support\Facades\Cache;



class EmployeeController extends Controller
{
    public function index(Request $request)
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
            $query->whereIn('dept', $deptFilter);
        }

        // Terapkan filter pencarian jika ada
        if (!empty($searchQuery)) {
            $query->where('badge_no', 'like', '%' . $searchQuery . '%');
        }

        // Ambil data peserta dengan filter
        $peserta_records = $query->paginate();

        
        return view('content.employee', [
            'peserta_records' => $peserta_records,
            'deptFilter' => $deptFilter, // Kirimkan filter ke view untuk mempertahankan nilai filter
            'searchQuery' => $searchQuery, // Kirimkan pencarian ke view untuk mempertahankan nilai pencarian
            'uniqueDepts' => $uniqueDepts, // Kirimkan nilai dept unik ke view
        ]);
    }


    public function show($id)
    {
        // Ambil data peserta berdasarkan ID
        $peserta = Peserta::find($id);

        if (!$peserta) {
            return response()->json(['error' => 'Peserta not found'], 404);
        }

        // Key untuk caching
        $cacheKey = "peserta_records:{$id}";

        // Ambil semua training_record yang terkait dengan peserta tersebut melalui relasi many-to-many
        $all_records = Cache::remember($cacheKey, 3600, function () use ($peserta) {
            return $peserta
                ->trainingRecords() // Pastikan menggunakan relasi many-to-many dari model Peserta
                ->with(['trainingCategory:id,name'])
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
}
