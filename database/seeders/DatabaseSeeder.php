<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(pesertaSeeder::class);
        $this->call(trainingrecordSeeder::class);
        $this->call(HasilPesertaSeeder::class);
        $this->call(TrainingSkillSeeder::class);
    }
}
