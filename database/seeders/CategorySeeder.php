<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('categories')->insert([
            ['name' => 'Neo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Project', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'External', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
