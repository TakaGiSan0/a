<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;



class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dan input pencarian dari request
        $deptFilter = $request->input('dept', []); // Pastikan deptFilter adalah array
        $searchQuery = $request->input('searchQuery');

        // Pastikan deptFilter adalah array
        if (is_string($deptFilter)) {
            $deptFilter = explode(',', $deptFilter); // Ubah string menjadi array jika perlu
        }

        // Dapatkan semua nilai dept unik dari tabel peserta
        $uniqueDepts = Peserta::select('dept')->distinct()->pluck('dept')->toArray(); // Konversi ke array

        // Mulai dengan query peserta
        $query = Peserta::select("id", "badge_no", "employee_name", "dept", "position")
        ->when($searchQuery, function ($query, $searchQuery) {
            $query->where('badge_no', 'like', '%' . $searchQuery . '%')
                  ->orWhere('employee_name', 'like', '%' . $searchQuery . '%');
        })
        ->orderBy('employee_name', 'asc');

        // Terapkan filter berdasarkan dept jika ada
        if (!empty($deptFilter) && is_array($deptFilter)) {
            $query->whereIn('dept', $deptFilter);
        }


        // Ambil data peserta dengan filter
        $peserta_records = $query->paginate(10);


        return view('content.employee', [
            'peserta_records' => $peserta_records,
            'deptFilter' => $deptFilter, // Kirimkan filter ke view untuk mempertahankan nilai filter
            'searchQuery' => $searchQuery, // Kirimkan pencarian ke view untuk mempertahankan nilai pencarian
            'uniqueDepts' => $uniqueDepts, // Kirimkan nilai dept unik ke view
        ]);
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Ambil data peserta berdasarkan ID
        $peserta = Peserta::with(['trainingRecords' => function ($query) {
            $query->withPivot('level', 'final_judgement');
        }])->findOrFail($id);

        if (!$peserta) {
            return response()->json(['error' => 'Peserta not found'], 404);
        }

        // Ambil semua training_record yang terkait dengan peserta tersebut melalui relasi many-to-many
        $all_records = $peserta
            ->trainingRecords() // Pastikan menggunakan relasi many-to-many dari model Peserta
            ->with(['trainingCategory:id,name'])
            ->get();

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
    public function downloadPdf($id)
    {
        // Ambil data peserta berdasarkan ID
        $peserta = Peserta::with(['trainingRecords' => function ($query) {
            $query->withPivot('level', 'final_judgement');
        }])->findOrFail($id);

        if (!$peserta) {
            return response()->json(['error' => 'Peserta not found'], 404);
        }

        // Ambil semua training_record yang terkait dengan peserta tersebut melalui relasi many-to-many
        $all_records = $peserta
            ->trainingRecords() // Pastikan menggunakan relasi many-to-many dari model Peserta
            ->with(['trainingCategory:id,name'])
            ->get();

        // Kelompokkan data berdasarkan category_id
        $grouped_records = $all_records->groupBy('category_id');

        // Jika tidak ada data pelatihan, set grouped_records ke null
        if ($all_records->isEmpty()) {
            $grouped_records = null;
        }

        // Generate PDF
        $pdf = PDF::loadView('pdf.training_employee', [
            'peserta' => $peserta,
            'grouped_records' => $grouped_records,
        ]);

        // Download PDF
        return $pdf->download($peserta->employee_name  . ' Training Record.pdf');
    }
}
