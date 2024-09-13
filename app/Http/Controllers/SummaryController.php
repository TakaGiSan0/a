<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\training_record;
use Barryvdh\DomPDF\Facade\pdf;


class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $training_records = training_record::with(['trainingCategory:id,name']);

        // Filter tanggal
        if ($request->has('tanggal')) {
            $training_records = $training_records->whereDate('created_at', $request->input('tanggal'));
        }

        // Filter dept
        if ($request->has('station')) {
            $training_records = $training_records->where('station', $request->input('station'));
        }

        // Filter training_category
        if ($request->has('training_category')) {
            $training_records = $training_records->where('category_id', $request->input('training_category'));
        }

        // Search training_name
        if ($request->has('search')) {
            $training_records = $training_records->where('training_name', 'like', '%' . $request->input('search') . '%');
        }

        $training_records = $training_records->get();

        return view('superadmin.summary', compact('training_records'));
    }

    public function show($id)
    {
        // Ambil semua training records berdasarkan id
        $trainingRecords = Training_Record::with(['pesertas', 'trainingCategory'])
            ->where('id', $id)
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
                'status' => $record->status,
                'peserta' => $peserta,
            ];
        });

        return response()->json($trainingWithPeserta)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }


    public function search(Request $request)
    {
        $query = Training_Record::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('training_name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dept') && $request->dept != '') {
            $query->where('dept', $request->dept);
        }

        if ($request->has('station') && $request->station != '') {
            $query->where('station', $request->station);
        }

        if ($request->has('training_date') && $request->training_date != '') {
            $query->whereDate('training_date', $request->training_date);
        }

        if ($request->has('training_category') && $request->training_category != '') {
            $query->where('category_id', $request->training_category);
        }

        $results = $query->get();

        return response()->json($results);
    }

    public function downloadSummaryPdf($id)
    {
        // Ambil data summary berdasarkan id
        $trainingRecord = Training_Record::findOrFail($id);

        // Menyiapkan data untuk view PDF
        $data = [
            'training_name' => $trainingRecord->training_name,
            'doc_ref' => $trainingRecord->doc_ref,
            'job_skill' => $trainingRecord->job_skill,
            'trainer_name' => $trainingRecord->trainer_name,
            'rev' => $trainingRecord->rev,
            'station' => $trainingRecord->station,
            'training_date' => $trainingRecord->training_date,
            'skill_code' => $trainingRecord->skill_code,
            'status' => $trainingRecord->status,
            'participants' => $trainingRecord->pesertas, // Mengambil relasi peserta jika ada
        ];

        try {
            // Render view ke PDF
            $pdf = Pdf::loadView('pdf.training_summary', $data);

            // Unduh file PDF
            return $pdf->download('training_summary.pdf');
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            dd($e);

            // Redirect atau tampilkan pesan kesalahan
            return redirect()->route('superadmin.dashboard')->with('error', 'Terjadi kesalahan saat membuat PDF.');
        }
    }

}
