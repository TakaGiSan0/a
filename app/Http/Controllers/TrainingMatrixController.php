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
    $records = Training_Record::with(['hasil_peserta.pesertas'])->get();

    // Kumpulkan semua station unik
    $stations = collect($records)->flatMap(function ($record) {
        return explode(', ', $record->station);
    })->unique()->values()->toArray();

    // Kumpulkan semua skill code unik
    $skillCodes = collect($records)->flatMap(function ($record) {
        return explode(', ', $record->skill_code);
    })->unique()->values()->toArray();

    // Ambil hanya peserta yang pernah ikut training (ada di hasil_peserta)
    $pesertas = Peserta::whereHas('trainingRecords') // Menyaring hanya peserta yang ada di trainingRecords
        ->with(['trainingRecords' => function ($query) {
            $query->withPivot('level');
        }])->get();

    return view('content.training_matrix', compact('stations', 'pesertas', 'records', 'skillCodes'));
}

}
