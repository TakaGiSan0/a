<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\TrainingRecord;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;



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

        $user = Auth::user();

        $query = Peserta::byDept()
            ->select("id", "badge_no", "employee_name", "dept", "position")
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($subQuery) use ($searchQuery) {
                    $subQuery->where('badge_no', 'like', "%{$searchQuery}%")
                        ->orWhere('employee_name', 'like', "%{$searchQuery}%");
                });
            })
            ->when(!empty($deptFilter), function ($query) use ($deptFilter) {
                $query->whereIn('dept', $deptFilter);
            })
            ->orderBy('employee_name', 'asc');



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
        $peserta = Peserta::with([
            'trainingRecords' => function ($query) {
                $query->where('status', 'Completed')
                    ->select([
                        'training_records.id as training_id', // Alias untuk menghindari ambiguitas
                        'training_records.training_name',
                        'training_records.doc_ref',
                        'training_records.trainer_name',
                        'training_records.rev',
                        'training_records.station',
                        'training_records.category_id',
                        'training_records.date_start',
                        'training_records.date_end',
                    ])
                    ->withPivot('level', 'final_judgement')
                    ->with(['trainingCategory:id,name']);
            }
        ])->findOrFail($id);

        if (!$peserta) {
            return response()->json(['error' => 'Peserta not found'], 404);
        }

        // Ambil semua training_record yang terkait dengan peserta tersebut melalui relasi many-to-many
        $all_records = $peserta
            ->trainingRecords() // Pastikan menggunakan relasi many-to-many dari model Peserta
            ->with(['trainingCategory:id,name'])
            ->where('status', 'Completed')
            ->get()
            ->map(function ($record) {
                return [
                    'training_name' => $record->training_name,
                    'doc_ref' => $record->doc_ref,
                    'job_skill' => $record->job_skill,
                    'trainer_name' => $record->trainer_name,
                    'rev' => $record->rev,
                    'station' => $record->station,
                    'skill_code' => $record->skill_code,
                    'date_formatted' => $record->formatted_date_range, // Gunakan accessor
                    'category_id' => $record->category_id,
                    'level' => $record->pivot->level,
                    'final_judgement' => $record->pivot->final_judgement,
                    'training_category' => $record->trainingCategory ? $record->trainingCategory->name : null,
                ];
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
