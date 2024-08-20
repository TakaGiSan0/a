<?php

namespace Database\Seeders;

use App\Models\final_judgement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;




class finalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('final_judgements')->insert([
            ['name' => 'Competence', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Attend', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
