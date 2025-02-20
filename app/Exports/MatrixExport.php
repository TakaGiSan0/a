<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\hasil_peserta;
use App\Models\training_record;
use Maatwebsite\Excel\Concerns\WithHeadings;


class MatrixExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return hasil_peserta::where('license', 1)
            ->whereHas('trainingrecord', function ($query) {
                $query->where('status', 'completed'); // Filter untuk status completed
            })
            ->with(['trainingrecord', 'pesertas']) // Eager load relasi trainingrecord dan pesertas
            ->get()
            ->map(function ($item) {
                return [
                    'badge_no' => $item->pesertas->badge_no,
                    'employee_name' => $item->pesertas->employee_name,
                    'dept' => $item->pesertas->dept,
                    'training_name' => $item->trainingrecord->training_name,
                    'date_start' => $item->trainingrecord->getFormattedDateRangeAttribute(),
                    'certificate' => $item->certificate,
                    'expired_date' => $item->expired_date,
                    'category' => $item->category,
                    'Status' => $item->getStatusAttribute(),
                ];
            });
    }

    public function headings(): array
    {
        return [    
            'Badge No',
            'Employee Name',
            'Dept',
            'Training Name',
            'Training Date',
            'certificate No',
            'Expired Date',
            'Category',
            'Status'
        ];
    }
}
