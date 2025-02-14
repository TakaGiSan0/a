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
            // Jika format angka (Excel date)
            $trainingDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['training_date']);
            $formattedDateStart = $trainingDate->format('Y-m-d');
            $formattedDateEnd = $trainingDate->format('Y-m-d');
        } else {
            // Jika format teks seperti '18 Mar-15 Apr 2021' atau '28-30 Dec 20'
            $dates = $this->parseDateRange($row['training_date']);
            $formattedDateStart = $dates['start'];
            $formattedDateEnd = $dates['end'];
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
            'date_start' => $formattedDateStart,
            'date_end' => $formattedDateEnd,
            'trainer_name' => $row['trainer_name'],
            'category_id' => $category->id,
            'status' => 'completed',
            'approval' => 'Approved',

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

    private function parseDateRange($dateRange)
{
    // Ambil tahun dari string (jika ada)
    preg_match('/\d{4}/', $dateRange, $yearMatch);
    $year = $yearMatch[0] ?? date('Y');

    // Ambil dua bagian tanggal (start-end)
    $parts = preg_split('/[-–]/', $dateRange);
    $startPart = trim($parts[0]);
    $endPart = trim($parts[1] ?? $startPart);

    // Format start date
    $startDate = DateTime::createFromFormat('d M Y', "$startPart $year") 
                  ?? DateTime::createFromFormat('d/m/Y', "$startPart/$year") 
                  ?? DateTime::createFromFormat('d M y', "$startPart $year");

    // Coba format end date
    $endDate = DateTime::createFromFormat('d M Y', "$endPart $year") 
                ?? DateTime::createFromFormat('d/m/Y', "$endPart/$year") 
                ?? DateTime::createFromFormat('d M y', "$endPart $year");

    return [
        'start' => $startDate ? $startDate->format('Y-m-d') : '1970-01-01',
        'end' => $endDate ? $endDate->format('Y-m-d') : '1970-01-01',
    ];
}
}
