<?php
namespace App\Imports;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TrainingRecordsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buat atau temukan training record
        $trainingRecord = Training_Record::firstOrCreate([
            'doc_ref' => $row['doc_ref'],
            'training_name' => $row['training_name'],
            'job_skill' => $row['job_skill'],
            'trainer_name' => $row['trainer_name'],
            'rev' => $row['rev'],
            'station' => $row['station'],
            'training_date' => $row['training_date'],
            'skill_code' => $row['skill_code'],

        ]);

        // Buat atau temukan peserta
        $peserta = Peserta::firstOrCreate([
            'employee_name' => $row['employee_name'],
            'dept' => $row['dept'],
            'position' => $row['position'],
            'level' => $row['level'],
            'final_judgement' => $row['final_judgement'],
            'license' => $row['license'],
            'theory_result' => $row['theory_result'],
            'practical_result' => $row['practical_result'],
        ]);

        // Sambungkan training record dan peserta melalui tabel pivot
        $trainingRecord->peserta()->attach($peserta->id, [
            'hasil' => $row['hasil'],
        ]);

        return $trainingRecord;
    }
}