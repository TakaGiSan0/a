<?php

namespace App\Http\Controllers;

use App\Models\Training_Record;
use App\Models\Peserta;

use Illuminate\Http\Request;

class TrainingMatrixController extends Controller
{
    public function index()
    {
        // Ambil data training record dengan station dan peserta yang mengikuti training
        $records = Training_Record::with(['hasil_peserta.pesertas'])
            ->where('status', 'completed')
            ->get();

        // Kumpulkan semua station unik
        $stations = collect($records)->flatMap(function ($record) {
            return explode(', ', $record->station);
        })->unique()->values()->toArray();

        // Kumpulkan semua skill code unik
        $skillCodes = collect($records)->flatMap(function ($record) {
            return explode(', ', $record->skill_code);
        })->unique()->values()->toArray();

        // Ambil peserta dengan training record yang statusnya completed
        $pesertas = Peserta::whereHas('trainingRecords', function ($query) {
            $query->where('status', 'completed');
        })
            ->with(['trainingRecords' => function ($query) {
                $query->where('status', 'completed')
                    ->withPivot('level');
            }])
            ->get();

        // Hitung jumlah peserta
        $pesertaCount = Peserta::whereHas('trainingRecords', function ($query) {
            $query->where('status', 'completed');
        })->count();

        // Menyiapkan data untuk menghitung peserta dengan level 3 dan 4 pada setiap station
        $stationsWithLevels = [];

        $stationsWithLevels = [];

        foreach ($stations as $station) {
            // Menghitung jumlah level 3 dan 4 untuk setiap station
            $countLevel3And4 = $pesertas->filter(function ($peserta) use ($station) {
                return $peserta->trainingRecords->filter(function ($training) use ($station) {
                    // Pastikan 'station' ada dan tidak null
                    if (empty($training->station)) {
                        return false;
                    }

                    // Cek jika 'station' sudah berupa array
                    $stationsArray = is_array($training->station) ? $training->station : explode(', ', $training->station);

                    // Periksa apakah station ada dalam daftar dan apakah levelnya 3 atau 4
                    return in_array($station, $stationsArray) && in_array($training->pivot->level, ['3', '4']);
                })->isNotEmpty();
            })->count();

            $stationsWithLevels[$station] = $countLevel3And4;
        }

        $stationsWithGaps = [];
        foreach ($stationsWithLevels as $station => $count) {
            $stationsWithGaps[$station] = $pesertaCount - $count;
        }



        return view('content.training_matrix', compact('stations', 'pesertas', 'records', 'skillCodes', 'pesertaCount', 'stationsWithLevels', 'stationsWithGaps'));
    }
}
