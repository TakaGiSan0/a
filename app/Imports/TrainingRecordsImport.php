<?php

namespace App\Imports;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Models\Category;
use App\Models\Hasil_Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;

class TrainingRecordsImport implements ToModel, WithHeadingRow
{
    // Fungsi untuk memulai dari baris ke-2 karena baris pertama adalah header
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Fungsi model untuk mengimpor data.
     * 
     * @param array $row
     * 
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {   
        if (!empty($row['training_category'])) {
            $category = Category::firstOrCreate(['name' => $row['training_category']], ['id' => $this->getCategoryId($row['training_category'])]);
        } else {
            // Set default category jika kosong
            $category = Category::firstOrCreate(['name' => 'N/A']);
        }
        if (is_numeric($row['training_date'])) {
            // Excel date serial number to Unix timestamp conversion
            $trainingDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['training_date']);
            $formattedTrainingDate = $trainingDate->format('Y-m-d');
        } else {
            // Jika tidak numeric, parsing seperti biasa
            $trainingDate = DateTime::createFromFormat('d/m/Y', $row['training_date']);

            if ($trainingDate) {
                $formattedTrainingDate = $trainingDate->format('Y-m-d');
            } else {
                $formattedTrainingDate = '1970-01-01';
            }
        }



        // Cek apakah peserta sudah ada berdasarkan badge_no
        $peserta = Peserta::where('badge_no', $row["badge_no"])->first();

        // Jika peserta tidak ditemukan, abaikan
        if (!$peserta) {
            return; // Abaikan dan tidak melakukan apa-apa
        }


        // Tambahkan data training_record jika belum ada
        $trainingRecord = Training_Record::firstOrCreate([
            'doc_ref' => $row['doc_ref'],
            'rev' => $row['rev'],
            'training_name' => $row['training_name'],
            'station' => $row['station'],
            'job_skill' => $row['job_skill'],
            'skill_code' => $row['skill_code'],
            'training_date' => $formattedTrainingDate,
            'trainer_name' => $row['trainer_name'],
            'category_id' => $category->id,

        ]);

        // Tambahkan data ke tabel pivot hasil_peserta
        // Buat record di tabel pivot hasil_peserta
            Hasil_Peserta::create([
                'training_record_id' => $trainingRecord->id,
                'peserta_id' => $peserta->id,
                'theory_result' => $row['theory_result'],
                'practical_result' => $row['practical_result'],
                'level' => $row['level'],
                'final_judgement' => $row['final_judgement'],
                'license' => (!empty($row['license']) && $row['license'] == ['✔', '☑']) ? 1 : 0,

            ]);
    }

    /**
     * Dapatkan category_id berdasarkan nama kategori.
     *
     * @param string $categoryName
     * @return int
     */
    private function getCategoryId($categoryName)
    {
        switch (strtolower($categoryName)) {
            case 'NEO':
                return 1; // Jika category adalah 'NEO', maka category_id adalah 1
            case 'PROJECT':
                return 2;
            case 'INTERNAL':
                return 3;
            case 'EXTERNAL':
                return 4;
            default:
                return Category::firstOrCreate(['name' => $categoryName])->id; // Tambahkan jika belum ada
        }
    }
}
