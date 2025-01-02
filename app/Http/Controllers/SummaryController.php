<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\peserta;
use Illuminate\Http\Request;
use App\Models\Training_Record;

use Barryvdh\DomPDF\Facade\Pdf;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $training_date = $request->input('training_date');
        $search = $request->input('search');
         $station = $request->input('station');

        $trainingRecords = Training_Record::with(['trainingCategory:id,name'])
        ->where('status', 'Completed')
            ->when($search, function ($query, $search) {
                $query->where('training_name', 'like', '%' . $search . '%');
            })
            ->when($training_date, function ($query, $training_date) {
                $query->whereDate('training_date', $training_date);
            })
            ->when(request('category'), function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($station, function ($query, $station) {
                $query->where('station', $station);
            })
            ->orderBy('training_date', 'desc')
            ->paginate(10);

        $training_categories = Category::all();
        $station = training_record::select('station')->distinct()->get();

        return view('content.summary', compact('trainingRecords', 'training_categories', 'training_date', 'search', 'station'));
    }

    public function show($id)
    {
        $trainingRecords = Training_Record::with(['pesertas', 'trainingCategory'])
            ->where('id', $id)
            ->get();

        if ($trainingRecords->isEmpty()) {
            return response()->json(['error' => 'No records found'], 404);
        }

        $trainingWithPeserta = $trainingRecords->map(function ($record) {
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
                'peserta' => $record->pesertas,
            ];
        });

        return response()->json($trainingWithPeserta)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function search(Request $request)
    {
        $query = Training_Record::query();

        if ($request->filled('search')) {
            $query->where('training_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('dept')) {
            $query->where('dept', $request->dept);
        }

        if ($request->filled('station')) {
            $query->where('station', $request->station);
        }

        if ($request->filled('training_date')) {
            $query->whereDate('training_date', $request->training_date);
        }

        if ($request->filled('training_category')) {
            $query->where('category_id', $request->training_category);
        }

        $results = $query->get();

        return response()->json($results);
    }

    public function downloadSummaryPdf($id)
    {
        $trainingRecord = Training_Record::with('pesertas')->findOrFail($id);
        $no = 0;

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
            'event_number' => $no + 1,
            'participants' => $trainingRecord->pesertas->map(function ($peserta) {
                return [
                    'badge_no' => $peserta->badge_no,
                    'employee_name' => $peserta->employee_name,
                    'dept' => $peserta->dept,
                    'position' => $peserta->position,
                    'level' => $peserta->pivot->level,
                    'final_judgement' => $peserta->pivot->final_judgement,
                    'license' => $peserta->pivot->license,
                    'theory_result' => $peserta->pivot->theory_result,
                    'practical_result' => $peserta->pivot->practical_result,
                ];
            }),
        ];

        try {
            $pdf = Pdf::loadView('pdf.training_summary', $data);
            $formattedDate = \Carbon\Carbon::parse($trainingRecord->training_date)->format('Y-m-d');
            $fileName = 'Training Summary ' . $formattedDate . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.index')->with('error', 'Terjadi kesalahan saat membuat PDF.');
        }
    }
}
