<?php

namespace App\Http\Controllers;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Exports\TrainingMatrixExport;
use App\Models\Hasil_Peserta;
use App\Models\training_skill;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainingMatrixController extends Controller
{
    // File: app/Http/Controllers/YourController.php

    public function index(Request $request)
    {

        // Ambil daftar departemen (Kode Anda sudah bagus)
        $departments = Peserta::whereHas('trainingRecords', fn($q) => $q->where('status', 'completed'))
            ->distinct()->pluck('dept')->toArray();

        // Ambil data untuk legenda (Kode Anda sudah bagus)
        $legendSkills = Training_Skill::select('skill_code', 'job_skill')->get();

        // Standarkan pengambilan data master untuk header tabel
        $masterSkills = Training_Skill::withTrashed()
            ->where('skill_code', '!=', 'NA')
            ->where('skill_code', '!=', 'N/A')
            ->whereNotNull('skill_code') // Memastikan tidak null
            ->where('skill_code', '!=', '')     // Memastikan tidak string kosong
            ->get()
            ->keyBy('skill_code');
        $allStations = Training_Record::where('status', 'Completed')
            ->pluck('station')
            ->flatMap(fn($s) => explode(', ', $s))
            ->unique()
            // --- TAMBAHKAN FILTER DI SINI ---
            ->filter(function ($station) {
                // Hanya ambil station yang tidak kosong dan bukan 'NA' atau 'N/A'
                $trimmedStation = trim($station);
                $upperStation = strtoupper($trimmedStation);
                return !empty($trimmedStation) && $upperStation !== 'NA' && $upperStation !== 'N/A';
            })
            ->sort()
            ->values();

        $user = auth('')->user();

        // Query untuk peserta
        $pesertasQuery = Peserta::query()
            ->ByDept()
            ->with([
                'trainingRecords' => function ($query) {
                    // FILTER DI SINI: Hanya muat record yang statusnya 'Completed'.
                    $query->where('status', 'completed')
                        // DAN muat juga relasi skill dari record yang completed itu.
                        ->with('training_Skills'); // Pastikan nama relasi camelCase: trainingSkills
                }
            ])
            ->whereHas('trainingRecords', fn($q) => $q->where('status', 'completed'))
            ->where(function ($query) {
                $query->whereHas('trainingRecords', function ($q) {
                    $q->where('status', 'Completed')
                        ->where(function ($subq) {
                            $subq->whereNotNull('status')
                                ->where('station', '!=', '')
                                ->whereRaw("UPPER(station) NOT IN ('NA', 'N/A')");
                        });
                })->orWhereHas("trainingRecords.training_Skills", function ($q) {
                    $q->whereNotNull('skill_code')
                        ->where('skill_code', '!=', '')
                        ->whereRaw("UPPER(station) NOT IN ('NA', 'N/A')");
                });
            })
            ->when($request->dept, fn($q) => $q->whereIn('dept', (array) $request->dept))
            ->when($request->searchQuery, fn($q) => $q->where(function ($subq) use ($request) {
                $subq->where('employee_name', 'like', "%{$request->searchQuery}%")
                    ->orWhere('badge_no', 'like', "%{$request->searchQuery}%");
            }))

            ->orderBy('employee_name');

        // Ambil total sebelum paginasi (Kode Anda sudah bagus)
        $totalParticipants = (clone $pesertasQuery)->count();

        // Ambil peserta dengan paginasi
        $pesertas = $pesertasQuery->select('id', 'badge_no', 'join_date', 'employee_name', 'dept')->paginate(10);

        $pesertas->through(function ($peserta) use ($allStations, $masterSkills) {

            // --- Proses Stations untuk peserta ini ---
            $stationLevels = [];
            foreach ($peserta->trainingRecords as $record) {
                $levels = explode(', ', $record->pivot->level ?? '');
                $stationsInRecord = explode(', ', $record->station);
                foreach ($stationsInRecord as $index => $stationName) {
                    if (!isset($stationLevels[$stationName]))
                        $stationLevels[$stationName] = [];
                    $stationLevels[$stationName][] = $levels[$index] ?? null;
                }
            }

            $stationResults = [];
            foreach ($allStations as $station) {
                $levelsForStation = collect($stationLevels[$station] ?? [])->filter();
                $angkaLevels = $levelsForStation->filter(fn($l) => is_numeric($l))->all();
                $naLevels = $levelsForStation->filter(fn($l) => strtoupper($l) === 'NA')->isNotEmpty();
                $stationResults[$station] = !empty($angkaLevels) ? max($angkaLevels) : ($naLevels ? 'NA' : '-');
            }

            // --- Proses Skills untuk peserta ini ---
            $ownedSkills = $peserta->trainingRecords->pluck('training_Skills')->flatten()->pluck('skill_code')->unique();
            $skillLookup = $ownedSkills->flip();

            // "Hias" objek peserta dengan data yang sudah jadi
            $peserta->processed_stations = $stationResults;
            $peserta->processed_skills_lookup = $skillLookup;

            return $peserta;
        });

        // ... Kode Anda untuk menghitung $stationsWithLevels dan $stationsWithGaps ...
        // Sebaiknya perhitungan ini juga dioptimalkan, tapi kita fokus pada matriks utama dulu.

        $allPesertaIds = $pesertas->pluck('id');
        $stationsWithLevels = Hasil_Peserta::whereIn('peserta_id', $allPesertaIds)
            ->whereIn('level', ['3', '4'])
            ->join('training_records', 'hasil_peserta.training_record_id', '=', 'training_records.id')
            ->where('status', 'completed')
            ->groupBy('station')
            ->selectRaw('station, COUNT(*) as total')
            ->pluck('total', 'station')
            ->all();


        $stationsWithLevels = collect($allStations)
            ->mapWithKeys(
                fn($station) => [
                    $station => $stationsWithLevels[$station] ?? 0,
                ],
            )
            ->toArray();

        $stationsWithGaps = collect($stationsWithLevels)
            ->mapWithKeys(
                fn($count, $station) => [
                    $station => $totalParticipants - $count,
                ],
            )
            ->toArray();

        // =========================================================================
        // FASE 4: KIRIM DATA KE VIEW
        // =========================================================================

        return view('content.training_matrix', [
            'pesertas' => $pesertas,
            'stationsWithLevels' => $stationsWithLevels, // Anda sudah punya ini
            'stationsWithGaps' => $stationsWithGaps,    // Anda sudah punya ini
            'allStations' => $allStations,
            'masterSkills' => $masterSkills, // Kirim ini untuk header & loop
            'departments' => $departments,
            'searchQuery' => $request->searchQuery,
            'legendSkills' => $legendSkills,
        ]);
    }

    public function downloadpdf(Request $request)
    {
        $dept = $request->input('dept');
        $deptList = is_array($dept) ? implode(', ', $dept) : $dept;

        // =========================================================================
        // FASE 1: AMBIL SEMUA DATA DARI DATABASE DENGAN EFISIEN
        // =========================================================================

        // 1.1. Ambil semua skill master untuk kolom laporan.
        $masterSkills = Training_skill::withTrashed()->get()->keyBy('skill_code');

        // 1.2. Ambil semua station master.
        $masterStations = Training_Record::where('status', 'completed')
            ->pluck('station')
            ->flatMap(fn($s) => explode(', ', $s))
            ->unique()
            ->sort()
            ->values();

        // 1.3. Query utama untuk mengambil peserta + semua relasinya (Eager Loading).
        $allPeserta = Peserta::query()
            ->whereHas('trainingRecords', fn($q) => $q->where('status', 'completed'))
            ->when($dept, function ($query) use ($dept) {
                is_array($dept) ? $query->whereIn('dept', $dept) : $query->where('dept', $dept);
            })
            ->with([
                // Eager Load relasi untuk menghindari N+1 Query
                'trainingRecords' => function ($query) {
                    $query->where('status', 'completed')->with('training_Skills');
                }
            ])
            ->orderBy('employee_name', 'asc')
            ->get();

        // =========================================================================
        // FASE 2: PROSES DATA MENTAH MENJADI PETA (MAP) YANG CEPAT DIAKSES
        // =========================================================================

        $participantDataMap = [];
        foreach ($allPeserta as $peserta) {
            // --- Proses SKILL ---
            $ownedSkills = $peserta->trainingRecords->pluck('training_Skills')->flatten()->pluck('skill_code')->unique();
            $participantSkillLookup = $ownedSkills->flip();

            // --- Proses STATION ---
            $stationLevels = [];
            foreach ($peserta->trainingRecords as $record) {
                $levels = explode(', ', $record->pivot->level ?? '');
                $stationsInRecord = explode(', ', $record->station);
                foreach ($stationsInRecord as $index => $stationName) {
                    if (!isset($stationLevels[$stationName]))
                        $stationLevels[$stationName] = [];
                    $stationLevels[$stationName][] = $levels[$index] ?? null;
                }
            }

            $stationResults = [];
            foreach ($masterStations as $station) {
                $levelsForStation = collect($stationLevels[$station] ?? [])->filter();
                $angkaLevels = $levelsForStation->filter(fn($l) => is_numeric($l))->all();
                $naLevels = $levelsForStation->filter(fn($l) => strtoupper($l) === 'NA')->isNotEmpty();
                $stationResults[$station] = !empty($angkaLevels) ? max($angkaLevels) : ($naLevels ? 'NA' : '-');
            }

            // Simpan data yang sudah diproses ke dalam map
            $participantDataMap[$peserta->id] = [
                'skills' => $participantSkillLookup,
                'stations' => $stationResults,
            ];
        }

        // =========================================================================
        // FASE 3: BANGUN ARRAY FINAL UNTUK DIKIRIM KE VIEW
        // =========================================================================

        $results = [];
        $stationsWithSupply = array_fill_keys($masterStations->all(), 0);

        foreach ($allPeserta as $peserta) {
            $row = [
                'badge_no' => $peserta->badge_no,
                'employee_name' => $peserta->employee_name,
                'dept' => $peserta->dept,
                'join_date' => $peserta->join_date,
                'stations' => [],
                'skills' => [],
            ];

            // Isi data station & skill dari map yang sudah kita buat (sangat cepat)
            $stationData = $participantDataMap[$peserta->id]['stations'];
            $skillDataLookup = $participantDataMap[$peserta->id]['skills'];

            foreach ($masterStations as $station) {
                $levelTertinggi = $stationData[$station] ?? '-';
                $row['stations'][$station] = $levelTertinggi;
                if (in_array($levelTertinggi, ['3', '4'])) {
                    $stationsWithSupply[$station]++;
                }
            }

            foreach ($masterSkills as $skillCode => $skillModel) {
                $row['skills'][$skillCode] = isset($skillDataLookup[$skillCode]) ? '✓' : '-';
            }

            $results[] = $row;
        }

        $totalParticipants = count($allPeserta);
        $supplyRow = $stationsWithSupply;
        $gapRow = array_map(fn($supply) => $totalParticipants - $supply, $stationsWithSupply);

        $pdf = Pdf::loadView('pdf.training_matrix', compact('results', 'masterStations', 'masterSkills', 'supplyRow', 'gapRow', 'deptList'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Training_Matrix.pdf');
    }
}
