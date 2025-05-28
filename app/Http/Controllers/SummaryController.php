<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\peserta;
use Illuminate\Http\Request;
use App\Models\Training_Record;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $date_start = $request->input('date_start');
        $search = $request->input('search');
        $station = $request->input('station');

        $trainingRecords = Training_Record::select('id', 'training_name', 'status', 'date_start', 'category_id', 'station')
            ->with(['trainingCategory:id,name'])
            ->where('status', 'Completed')
            ->when($search, function ($query, $search) {
                $query->where('training_name', 'like', '%' . $search . '%');
            })
            ->when($date_start, function ($query, $date_start) {
                $query->whereDate('date_start', $date_start);
            })
            ->when(request('category'), function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($station, function ($query, $station) {
                $query->where('station', $station);
            })
            ->orderBy('date_start', 'desc')
            ->paginate(10);

        $training_categories = Category::all();
        $station = training_record::select('station')
        ->distinct()
        ->where('status', 'Completed')
        ->get();

        return view('content.summary', compact('trainingRecords', 'training_categories', 'date_start', 'search', 'station'));
    }

    public function show($id)
    {
        $trainingRecords = Training_Record::with(['pesertas', 'trainingCategory', 'training_skills'])
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
                'trainer_name' => $record->trainer_name,
                'rev' => $record->rev,
                'station' => $record->station,
                'date_formatted' => $record->formatted_date_range,
                'status' => $record->status,
                'peserta' => $record->pesertas,
                'skills' => $record->training_Skills->map(function ($skill) {
                    return [
                        'skill_code' => $skill->skill_code,
                        'job_skill' => $skill->job_skill,
                    ];
                }),
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

        if ($request->filled('date_start')) {
            $query->whereDate('date_start', $request->date_start);
        }

        if ($request->filled('training_category')) {
            $query->where('category_id', $request->training_category);
        }

        $results = $query->get();

        return response()->json($results);
    }

    public function downloadSummaryPdf($id)
    {
        $trainingRecord = Training_Record::with('pesertas', 'training_skills')->findOrFail($id);

        $trainingRecord->training_duration = \Carbon\Carbon::parse($trainingRecord->training_duration)->diffInMinutes(\Carbon\Carbon::parse('00:00:00'));
        $trainingRecord->training_duration = abs($trainingRecord->training_duration);

        $data = [
            'training_name' => $trainingRecord->training_name,
            'doc_ref' => $trainingRecord->doc_ref,
            'trainer_name' => $trainingRecord->trainer_name,
            'rev' => $trainingRecord->rev,
            'station' => $trainingRecord->station,
            'date_range' => $trainingRecord->formatted_date_range,
            'status' => $trainingRecord->status,
            'training_category' => $trainingRecord->trainingCategory->name,
            'training_duration' => $trainingRecord->training_duration,
            'no' => $trainingRecord->id,
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
            'skills' => $trainingRecord->training_skills->map(function ($skill) {
                return [
                    'skill_code' => $skill->skill_code,
                    'job_skill' => $skill->job_skill,
                ];
            }),
        ];

        try {
            $pdf = Pdf::loadView('pdf.training_summary', $data);
            $formattedDate = \Carbon\Carbon::parse($trainingRecord->training_date)->format('Y-m-d');
            $fileName = 'Training Summary ' . $formattedDate . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {

            return redirect()->route('dashboard.summary')->with('error', 'Terjadi kesalahan saat membuat PDF.');
        }
    }
}
