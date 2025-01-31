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
            ['user' => 'Super Admin','name' => 'santoso','role' => 'Super Admin', 'password' => bcrypt('superadmin'), 'department' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['user' => 'Admin','name' => 'ahmadi','role' => 'Admin', 'password' => bcrypt('admin'),'department' => 'Tooling' , 'created_at' => now(), 'updated_at' => now()],
            ['user' => 'user','name' => 'budianto','role' => 'user', 'password' => bcrypt('user') ,'department' => 'Quality' , 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
