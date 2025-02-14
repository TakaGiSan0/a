<?php

namespace App\Http\Controllers;

use App\Models\Training_Record;
use App\Models\Peserta;
use Illuminate\Http\Request;

class TrainingMatrixController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $searchQuery = $request->input('searchQuery');
        $deptFilters = $request->input('dept', []);

        $pesertasQuery = Peserta::whereHas('trainingRecords', fn($q) => $q->where('status', 'completed'));
        if (!empty($deptFilters)) {
            $pesertasQuery->whereIn('dept', $deptFilters);
        }
        if (!empty($searchQuery)) {
            $pesertasQuery->where(fn($q) => $q->where('employee_name', 'like', "%$searchQuery%")
                ->orWhere('badge_no', 'like', "%$searchQuery%"));
        }

        // Ambil peserta dengan pagination (10 per halaman)
        $pesertas = $pesertasQuery->with(['trainingRecords:id,status,station'])
            ->select('id', 'badge_no', 'join_date', 'employee_name', 'dept')
            ->orderBy('employee_name', 'asc')
            ->paginate(10);

        // Ambil hanya 10 training_record dengan pagination
        $records = Training_Record::with([
            'hasil_peserta:id,training_record_id,level',
            'hasil_peserta.pesertas:id,badge_no,nama,join_date,dept'
        ])
            ->where('status', 'completed')
            ->select('id', 'station', 'skill_code')
            ->paginate(10);

        // Ambil semua station dari seluruh training_record (tanpa pagination)
        $allStations = Training_Record::where('status', 'completed')
            ->pluck('station')
            ->unique()
            ->toArray();

        // Ambil unique stations & skill codes
        $stations = $records->pluck('station')->flatMap(fn($s) => explode(', ', $s))->unique()->values()->toArray();
        $skillCodes = $records->pluck('skill_code')->flatMap(fn($s) => explode(', ', $s))->unique()->values()->toArray();



        // Ambil data peserta dengan pagination


        $pesertaCount = $pesertas->total();

        // Hitung jumlah peserta dengan level 3 & 4 per station
        $stationsWithLevels = $pesertas->flatMap(function ($peserta) {
            return $peserta->trainingRecords->filter(fn($t) => in_array($t->pivot->level, ['3', '4']))
                ->pluck('station');
        })->countBy()->toArray();

        // Hitung gap peserta
        $stationsWithGaps = collect($stationsWithLevels)->mapWithKeys(fn($count, $station) => [
            $station => $pesertaCount - $count
        ])->toArray();

        // Ambil daftar departemen
        $departments = Peserta::distinct('dept')->pluck('dept');

        return view('content.training_matrix', compact(
            'stations',
            'pesertas',
            'records',
            'skillCodes',
            'pesertaCount',
            'stationsWithLevels',
            'stationsWithGaps',
            'searchQuery',
            'deptFilters',
            'departments',
            'allStations'
        ));
    }
}
