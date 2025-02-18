<?php

namespace App\Http\Controllers;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Models\Hasil_Peserta;
use Illuminate\Http\Request;

class TrainingMatrixController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar semua departemen (tanpa filter)
        $departments = Peserta::distinct()->pluck('dept')->toArray();

        // Ambil peserta dengan filter yang dipilih
        $pesertasQuery = Peserta::whereHas('trainingRecords', function ($query) {
            $query->where('status', 'completed');
        })
            ->when($request->dept, fn($q) => $q->whereIn('dept', (array) $request->dept))
            ->when(
                $request->searchQuery,
                fn($q) =>
                $q->where('employee_name', 'like', "%{$request->searchQuery}%")
                    ->orWhere('badge_no', 'like', "%{$request->searchQuery}%")
            )
            ->distinct();

        

        // Ambil peserta dengan paginasi
        $pesertas = $pesertasQuery->select('id', 'badge_no', 'join_date', 'employee_name', 'dept')
            ->orderBy('employee_name')
            ->paginate(10);

          
        // Cek total participants, gunakan dd untuk debugging
        $totalParticipants = $pesertas->total();


        // Ambil semua Peserta Id untuk digunakan pada penghitungan Level
        $allPesertaIds = Peserta::when($request->dept, function ($query) use ($request) {
            return $query->whereIn('dept', (array) $request->dept);
        })->pluck('id');

        // Hitung Level 3 & 4 per Station
        $stationsWithLevels = Hasil_Peserta::whereIn('peserta_id', $allPesertaIds)
            ->whereIn('level', ['3', '4'])
            ->with('trainingrecord')
            ->get()
            ->groupBy('trainingrecord.station')
            ->map(fn($records) => $records->count())
            ->toArray();


        // Ambil semua station yang unik
        $allStations = Training_Record::where('status', 'completed')->pluck('station')->unique()->toArray();
        $allSkillCode = Training_Record::where('status', 'completed')
            ->pluck('skill_code')->flatMap(fn($s) => explode(', ', $s))->unique()->toArray();

        // Gabungkan station dengan jumlah level 3 & 4
        $stationsWithLevels = collect($allStations)->mapWithKeys(function ($station) use ($stationsWithLevels) {
            return [$station => $stationsWithLevels[$station] ?? 0];
        })->toArray();

        // Hitung gap per station
        $stationsWithGaps = collect($stationsWithLevels)->mapWithKeys(function ($count, $station) use ($totalParticipants) {
            return [$station => $totalParticipants - $count]; // Menghitung gap
        })->toArray();

        // View
        return view('content.training_matrix', [
            'pesertas' => $pesertas,
            'stationsWithLevels' => $stationsWithLevels,
            'stationsWithGaps' => $stationsWithGaps,
            'allStations' => $allStations,
            'allSkillCode' => $allSkillCode,
            'departments' => $departments,
            'searchQuery' => $request->searchQuery,
        ]);
    }
}
