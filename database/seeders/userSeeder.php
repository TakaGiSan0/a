<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['user' => 'super admin','name' => 'santoso','role' => 'superadmin', 'password' => bcrypt('superadmin'), 'created_at' => now(), 'updated_at' => now()],
            ['user' => 'admin','name' => 'ahmadi','role' => 'admin', 'password' => bcrypt('admin'), 'created_at' => now(), 'updated_at' => now()],
            ['user' => 'user','name' => 'budianto','role' => 'user', 'password' => bcrypt('user') , 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
