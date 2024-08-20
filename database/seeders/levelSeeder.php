<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\level;


class levelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('levels')->insert([
            ['level' => 'Level 1', 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Level 2', 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Level 3', 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Level 4', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
