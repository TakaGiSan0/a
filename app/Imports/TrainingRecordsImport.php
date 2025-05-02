<?php

namespace App\Imports;

use App\Models\Training_Record;
use App\Models\Peserta;
use App\Models\Category;
use App\Models\Hasil_Peserta;
use App\Models\training_comment;
use App\Models\training_skill;
use App\Models\trainingskillrecord;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
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
            $trainingDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['training_date']);
            $formattedDateStart = $trainingDate->format('Y-m-d');
            $formattedDateEnd = $trainingDate->format('Y-m-d');
        } else {
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
            'date_start' => $formattedDateStart,
            'date_end' => $formattedDateEnd,
            'trainer_name' => $row['trainer_name'],
            'category_id' => $category->id,
            'status' => 'completed',
            'user_id' => auth('web')->id(), // Ambil user_id dari session

        ]);
        // Tambahkan data ke tabel pivot hasil_peserta
        // Buat record di tabel pivot hasil_peserta
        Hasil_Peserta::firstOrCreate([
            'training_record_id' => $trainingRecord->id,
            'peserta_id' => $peserta->id,
            'theory_result' => $row['theory_result'],
            'practical_result' => $row['practical_result'],
            'level' => $row['level'],
            'final_judgement' => $row['final_judgement'],
            'license' => (!empty($row['license']) && $row['license'] == ['✔', '☑']) ? '1' : '0',
        ]);

        training_comment::firstOrCreate([
            'training_record_id' => $trainingRecord->id,
            'approval' => 'Approved',
        ]);

        $skillCodes = array_map('trim', explode(',', $row['skill_code']));
        $jobSkills  = array_map('trim', explode(',', $row['job_skill']));

        // Validasi jumlah pasangan harus sama
        if (count($skillCodes) !== count($jobSkills)) {
            Log::warning("Jumlah skill_code dan job_skill tidak cocok di baris: ", $row);
            return;
        }

        $seenCodes = []; // untuk melacak skill_code yang sudah diproses di baris ini

        foreach ($skillCodes as $i => $code) {
            // Jika skill_code sudah diproses di baris ini, lewati
            if (in_array($code, $seenCodes)) {
                continue;
            }
            $seenCodes[] = $code;

            // Cek apakah skill_code sudah ada di database
            $existing = training_skill::where('skill_code', $code)->first();

            if ($existing) {
                // Jika sudah ada, gunakan yang dari database (abaikan job_skill dari Excel)
                $trainingskill = $existing;
            } else {
                // Jika belum ada, tambahkan dari Excel
                $trainingskill = training_skill::create([
                    'skill_code' => $code,
                    'job_skill' => $jobSkills[$i],
                ]);
            }

            // Simpan relasi dengan training_record
            trainingskillrecord::firstOrCreate([
                'training_record_id' => $trainingRecord->id,
                'training_skill_id' => $trainingskill->id,
            ]);
        }
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

        preg_match('/(\d{2,4})$/', $dateRange, $yearMatch);

        $year = $yearMatch[1] ?? date('Y');

        // Ubah 2 digit tahun menjadi 4 digit (misal: 20 → 2020)
        if (strlen($year) == 2) {
            $year = (intval($year) > 30) ? "19$year" : "20$year";
        }

        // Hilangkan tahun dari string agar bisa fokus ke tanggal
        $dateRange = str_replace($yearMatch[0], '', $dateRange);

        // Pisahkan tanggal awal dan akhir
        $parts = preg_split('/[-–]/', $dateRange);
        $startPart = trim($parts[0] ?? '');
        $endPart = trim($parts[1] ?? $startPart);

        // Ambil bulan dari bagian akhir (biasanya ada bulan di sana)
        preg_match('/[A-Za-z]{3,}/', $endPart, $monthMatch);
        $month = $monthMatch[0] ?? '';

        // Jika bulan tidak ada di end, ambil dari start
        if (!$month) {
            preg_match('/[A-Za-z]{3,}/', $startPart, $monthMatch);
            $month = $monthMatch[0] ?? '';
        }

        // Ambil tanggal dari start dan end
        preg_match('/d{1,2}/', $startPart, $dayStartMatch);
        preg_match('/\d{1,2}/', $endPart, $dayEndMatch);

        $dayStart = $dayStartMatch[0] ?? '01';
        $dayEnd = $dayEndMatch[0] ?? '01';

        // Handle kasus "31–4 Apr 2023", 31 dianggap bulan sebelumnya
        $startMonth = $month;
        if ((int)$dayStart > 28 && (int)$dayEnd <= 12) {
            // Misalnya: 31–4 Apr → start date dianggap bulan sebelumnya
            $startMonth = date('M', strtotime("-1 month", strtotime("1 $month $year")));
        }

        // Buat objek DateTime
        $startDate = DateTime::createFromFormat('j M Y', "$dayStart $startMonth $year");

        $endDate = DateTime::createFromFormat('j M Y', "$dayEnd $month $year");

        return [
            'start' => $startDate ? $startDate->format('Y-m-d') : '1970-01-01',
            'end' => $endDate ? $endDate->format('Y-m-d') : '1970-01-01',
        ];
    }



    private function getPreviousMonthAbbreviation($monthAbbrev)
    {
        try {
            $date = \DateTime::createFromFormat('M', $monthAbbrev);
            $date->modify('-1 month');
            return $date->format('M'); // Mengembalikan bulan sebelumnya
        } catch (\Exception $e) {
            return $monthAbbrev;
        }
    }
}
