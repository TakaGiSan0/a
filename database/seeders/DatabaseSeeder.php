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
        $this->call(finalSeeder::class);
        $this->call(levelSeeder::class);
        $this->call(practicalSeeder::class);
        $this->call(theorySeeder::class);
        $this->call(userSeeder::class);
        $this->call(pesertaSeeder::class);

    }
}
