<?php

namespace App\Exports;

use App\Models\TrainingRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingRequestExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TrainingRequest::with('peserta')
            ->get()
            ->map(function ($item) {
                return [
                    'Nama'       => $item->peserta->employee_name ?? '-',
                    'Badge No'   => $item->peserta->badge_no ?? '-',
                    'Dept'       => $item->peserta->dept ?? '-',
                    'Deskripsi'  => $item->description,
                    'Tanggal'    => $item->created_at->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama', 'Badge No', 'Dept', 'Deskripsi', 'Tanggal'];
    }
}
