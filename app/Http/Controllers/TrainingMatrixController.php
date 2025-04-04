<?php

namespace App\Http\Controllers;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Exports\TrainingMatrixExport;
use App\Models\Hasil_Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainingMatrixController extends Controller
{
    public function index(Request $request)
{
    // Ambil daftar semua departemen dalam satu query
    $departments = Peserta::whereHas('trainingRecords', function ($query) {
        $query->where('status', 'completed');
    })->distinct()->pluck('dept')->toArray();

    // Query untuk peserta dengan filter yang dipilih
    $pesertasQuery = Peserta::with('trainingRecords')
        ->whereHas('trainingRecords', function ($query) {
            $query->where('status', 'completed');
        })
        ->when($request->dept, fn($q) => $q->whereIn('dept', (array) $request->dept))
        ->when(
            $request->searchQuery,
            fn($q) =>
            $q->where('employee_name', 'like', "%{$request->searchQuery}%")
                ->orWhere('badge_no', 'like', "%{$request->searchQuery}%")
        );

    // Ambil peserta dengan paginasi
    $pesertas = $pesertasQuery->select('id', 'badge_no', 'join_date', 'employee_name', 'dept')
        ->orderBy('employee_name')
        ->paginate(10);

    // Hitung total peserta tanpa query tambahan setelah pagination
    $totalParticipants = $pesertasQuery->count();

    // Ambil semua Peserta Id yang difilter
    $allPesertaIds = $pesertas->pluck('id');

    // Hitung Level 3 & 4 per Station menggunakan Query Langsung
    $stationsWithLevels = Hasil_Peserta::whereIn('peserta_id', $allPesertaIds)
        ->whereIn('level', ['3', '4'])
        ->join('training_records', 'hasil_peserta.training_record_id', '=', 'training_records.id')
        ->where('status', 'completed')
        ->groupBy('station')
        ->selectRaw('station, COUNT(*) as total')
        ->pluck('total', 'station')
        ->toArray();

    // Ambil semua station dan skill_code dari training_record dalam satu query
    $trainingRecords = Training_Record::where('status', 'completed')->get(['station']);

    $allStations = $trainingRecords->pluck('station')->unique()->toArray();

    // Gabungkan station dengan jumlah level 3 & 4
    $stationsWithLevels = collect($allStations)->mapWithKeys(fn($station) => [$station => $stationsWithLevels[$station] ?? 0])->toArray();

    // Hitung gap per station
    $stationsWithGaps = collect($stationsWithLevels)->mapWithKeys(fn($count, $station) => [$station => $totalParticipants - $count])->toArray();

    // Return view dengan data yang sudah dioptimasi
    return view('content.training_matrix', [
        'pesertas' => $pesertas,
        'stationsWithLevels' => $stationsWithLevels,
        'stationsWithGaps' => $stationsWithGaps,
        'allStations' => $allStations,
        'departments' => $departments,
        'searchQuery' => $request->searchQuery,
    ]);
}


    public function downloadpdf(Request $request)
    {
        $dept = $request->input('dept'); // Ambil filter dari request

        $deptList = is_array($dept) ? implode(', ', $dept) : $dept;

        $pesertas = Peserta::whereHas('trainingRecords', function ($query) {
            $query->where('status', 'completed');
        })
            ->when($dept, function ($query) use ($dept) {
                if (is_array($dept)) {
                    $query->whereIn('dept', $dept);
                } else {
                    $query->where('dept', $dept);
                }
            })
            ->orderBy('employee_name', 'asc')
            ->get();

        $allStations = Training_Record::where('status', 'completed')
            ->pluck('station')->unique()->toArray();

        $allSkillCodes = Training_Record::where('status', 'completed')
            ->pluck('skill_code')->flatMap(fn($s) => explode(', ', $s))->unique()->toArray();

        $data = [];
        $stationsWithSupply = array_fill_keys($allStations, 0);

        foreach ($pesertas as $peserta) {
            $row = [
                'badge_no'     => $peserta->badge_no,
                'employee_name' => $peserta->employee_name,
                'dept'         => $peserta->dept,
                'join_date'    => $peserta->join_date,
                'stations'     => [],
                'skills'       => [],
            ];

            // Tambahkan level berdasarkan station
            foreach ($allStations as $station) {
                $levels = $peserta->trainingRecords
                    ->filter(fn($training) => in_array($station, explode(', ', $training->station)), $station)
                    ->pluck('pivot.level')
                    ->toArray();

                $angkaLevels = array_filter($levels, fn($level) => is_numeric($level));
                $naLevels = array_filter($levels, fn($level) => strtoupper($level) === 'NA');

                $levelTertinggi = !empty($angkaLevels) ? max($angkaLevels) : (!empty($naLevels) ? 'NA' : '-');

                if (in_array($levelTertinggi, ['3', '4'])) {
                    $stationsWithSupply[$station]++;
                }

                $row['stations'][$station] = $levelTertinggi;
            }

            // Tambahkan skill code
            foreach ($allSkillCodes as $skill) {
                $hasTraining = $peserta->trainingRecords->contains(fn($training) => str_contains($training->skill_code, $skill));
                $row['skills'][$skill] = $hasTraining ? '✓' : '-';
            }

            $data[] = $row;
        }

        // Hitung total peserta setelah difilter
        $totalParticipants = count($pesertas);

        // Buat baris Supply dan Gap
        $supplyRow = array_map(fn($supply) => $supply, $stationsWithSupply);
        $gapRow = array_map(fn($supply) => $totalParticipants - $supply, $stationsWithSupply);

        // Render PDF
        $pdf = Pdf::loadView('pdf.training_matrix', compact('data', 'allStations', 'allSkillCodes', 'supplyRow', 'gapRow', 'deptList'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Training_Matrix.pdf');
    }
}
