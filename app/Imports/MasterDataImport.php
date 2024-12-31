<?php

namespace App\Imports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MasterDataImport implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2; // Mulai dari baris ke-2 (baris pertama adalah header)
    }    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Cek apakah badge_no sudah ada di database
        $peserta = Peserta::where('badge_no', $row[0])->first();

        if ($peserta) {
            // Jika sudah ada, jangan tambahkan
            return null;
        }

        return new Peserta([
            'badge_no'=> $row[0],
            'employee_name'=> $row[1],
            'dept'=> $row[2],
            'position'=> $row[3],
        ]);
        
    }
}
