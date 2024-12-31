<?php

namespace App\Exports;

use App\Models\training_record;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingRecordExport implements FromCollection, WithHeadings
{
    /**
     * Fungsi untuk mengambil data yang akan diekspor.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data dari database tanpa ROW_NUMBER()
        $data = DB::table('training_records')
        ->join('hasil_peserta', 'training_records.id', '=', 'hasil_peserta.training_record_id')
        ->join('pesertas', 'hasil_peserta.peserta_id', '=', 'pesertas.id')
        ->join('categories', 'training_records.category_id', '=', 'categories.id')  // Join ke tabel categories
            ->orderBy('training_date', 'desc')
            ->select(
                'doc_ref',
                'rev',
                'training_name',
                'station',
                'job_skill',
                'skill_code',
                'badge_no',
                'employee_name',
                'dept',
                'position',
                'training_date',
                'trainer_name',
                'theory_result',
                'practical_result',
                'level',
                'final_judgement',
                'categories.name as category_name',  // Ambil nama kategori
                'license'
            )
            
            ->get()
            ->map(function ($item) {
                // Ganti license dengan checkbox unicode
                $item->license = $item->license == 1 ? '☑' : '☐';
                return $item;
            });

        // Menambahkan nomor urut di posisi pertama
        $data = $data->map(function ($item, $key) {
            // Ubah $item menjadi array dan gabungkan dengan 'No' di awal array
            return array_merge(['No' => $key + 1], (array) $item);
        });

        return $data;
    }


    /**
     * Fungsi untuk menambahkan header di Excel.
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'DOC. REF',
            'REV',
            'Training Name',
            'Station',
            'Job Skill',
            'Skill Code',
            'Badge No',
            'Emp Name',
            'Dept',
            'Position',
            'Trainer Date',
            'Trainer Name',
            'Theory Result',
            'Practical Result',
            'Level',
            'Final Judgement',
            'Category',
            'License',
        ];
    }
}
