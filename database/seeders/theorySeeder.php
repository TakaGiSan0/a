<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\theory_result;


class theorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('theory_results')->insert([
            ['name' => 'PASS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'FAIL', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
