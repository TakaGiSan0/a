<?php

namespace App\Imports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class MasterDataImport implements ToModel
{
    /**
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
