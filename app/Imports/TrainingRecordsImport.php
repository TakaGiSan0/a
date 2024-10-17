<?php

namespace App\Imports;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Models\Category;
use App\Models\Hasil_Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DateTime;

class TrainingRecordsImport implements ToModel, WithStartRow
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
        // Cek atau tambahkan kategori berdasarkan nama kategori
        $category = Category::firstOrCreate(['name' => $row[17]], ['id' => $this->getCategoryId($row[17])]);

        // Ubah format tanggal dari Excel ke MySQL
        $trainingDate = DateTime::createFromFormat('d-M-y', $row[11]);

        if ($trainingDate) {
            // Jika parsing berhasil, format tanggal
            $formattedTrainingDate = $trainingDate->format('Y-m-d');
        } else {
            // Jika gagal, gunakan tanggal default atau log error
            $formattedTrainingDate = '1970-01-01'; // atau '1970-01-01' jika Anda ingin default
        }


        // Cek apakah peserta sudah ada berdasarkan badge_no
        $peserta = Peserta::where('badge_no', $row[7])->first();

        // Jika peserta tidak ditemukan, abaikan
        if (!$peserta) {
            return; // Abaikan dan tidak melakukan apa-apa
        }


        // Tambahkan data training_record jika belum ada
        $trainingRecord = Training_Record::firstOrCreate([
            'doc_ref' => $row[1],
            'rev' => $row[2],
            'training_name' => $row[3],
            'station' => $row[4],
            'job_skill' => $row[5],
            'skill_code' => $row[6],
            'training_date' => $formattedTrainingDate,
            'trainer_name' => $row[12],
            'category_id' => $category->id,

        ]);

        // Tambahkan data ke tabel pivot hasil_peserta
        // Buat record di tabel pivot hasil_peserta
        Hasil_Peserta::create([
            'training_record_id' => $trainingRecord->id,
            'peserta_id' => $peserta->id,
            'theory_result' => $row[13],
            'practical_result' => $row[14],
            'level' => $row[15],
            'final_judgement' => $row[16],
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
