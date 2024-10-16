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
        return DB::table('training_records')
        ->join('hasil_peserta', 'training_record.id', '=', 'hasil_peserta.training_record_id')
        ->join('pesertas', 'hasil_peserta.peserta_id', '=', 'pesertas.peserta.id')
            ->select(
                'doc_ref', 
                'training_name', 
                'job_skill', 
                'trainer_name',
                'rev',
                'station',
                'training_date',
                'skill_code',

                'badge_no',
                'employee_name',
                'dept',
                'position',

                'theory_result',
                'practical_result',
                'level',
                'final_judgement',
                'license'

            )
            ->get();
    }

    /**
     * Fungsi untuk menambahkan header di Excel.
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
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
            'License',

        ];
    }
}
