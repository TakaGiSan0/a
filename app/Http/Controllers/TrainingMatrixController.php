<?php

namespace App\Http\Controllers;

use App\Models\Training_Record;
use App\Models\Peserta;
use Illuminate\Http\Request;

class TrainingMatrixController extends Controller
{
    public function index(Request $request)
    {
        // Filter Peserta
        $pesertasQuery = Peserta::with(['trainingRecords' => fn($q) => $q->where('status', 'completed')])
            ->when($request->dept, fn($q) => $q->whereIn('dept', $request->dept))
            ->when($request->searchQuery, fn($q) =>
                $q->where('employee_name', 'like', "%{$request->searchQuery}%")
                ->orWhere('badge_no', 'like', "%{$request->searchQuery}%")
            )->distinct();

        $departments = $pesertasQuery->pluck('dept')->toArray();

        // Ambil peserta & training dengan pagination
        $pesertas = $pesertasQuery->select('id', 'badge_no', 'join_date', 'employee_name', 'dept')
            ->orderBy('employee_name')
            ->paginate(10);
        
        $records = Training_Record::select('id', 'station', 'skill_code')
            ->with('hasil_peserta.pesertas')
            ->where('status', 'completed')
            ->paginate(10);

        // Ambil semua station & skill code
        $allStations = Training_Record::where('status', 'completed')->pluck('station')->unique()->toArray();
        $allSkillCode = Training_Record::where('status', 'completed')
            ->pluck('skill_code')->flatMap(fn($s) => explode(', ', $s))->unique()->toArray();

        // Hitung Level 3 & 4 per Station
        $stationsWithLevels = $pesertas->flatMap(function ($peserta) {
            return $peserta->trainingRecords
                ->filter(fn($record) => in_array($record->pivot->level, ['3', '4']))
                ->pluck('station');
        })->countBy()->toArray();

        $stationsWithGaps = collect($stationsWithLevels)->mapWithKeys(function ($count, $station) use ($pesertas) {
            $totalParticipants = $pesertas->count(); // Total peserta
            return [$station => $totalParticipants - $count];
        })->toArray();

        // View
        return view('content.training_matrix', [
            'pesertas' => $pesertas,
            'records' => $records,
            'allSkillCode' => $allSkillCode,
            'stationsWithLevels' => $stationsWithLevels,
            'stationsWithGaps' => $stationsWithGaps,
            'allStations' => $allStations,
            'searchQuery' => $request->pesertaQuery,
            'departments' => $departments
        ]);
    }
}
