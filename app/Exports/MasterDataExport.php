<?php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Peserta::select('badge_no', 'employee_name', 'dept', 'position')->get();
    }

    public function headings(): array
    {
        return [
            'Badge No',
            'Employee Name',
            'Dept',
            'Position',
        ];
    }
}
