<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Peserta;


class pesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pesertas')->insert([
            ['badge_no' => 'P-7134-21','employee_name' => 'Titik Dwi Mutianiningsih','dept' => 'Purchasing','position' => 'Purchasing Senior Officer', 'created_at' => now(), 'updated_at' => now()],
            ['badge_no' => 'P-7450-24','employee_name' => 'Gatot Setiawan','dept' => 'Spray Paint','position' => 'Senior Supervisor', 'created_at' => now(), 'updated_at' => now()],
            ['badge_no' => 'P-6048-18','employee_name' => 'KusnaAli Kudlori','dept' => 'Molding 1','position' => 'Senior Supervisor', 'created_at' => now(), 'updated_at' => now()],
            ['badge_no' => 'C-6554-19','employee_name' => 'Khin Maung Hlaing','dept' => 'Molding 1','position' => 'Production Molding Manager', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
