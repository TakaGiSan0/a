<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Peserta;

class pesertaSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function run(): void
    {
        // 1. Buat akun Super Admin
        $superAdmin = User::create([
            'user' => 'superadmin',
            'role' => 'Super Admin',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat akun Admin
        $admin = User::create([
            'user' => 'admin',
            'role' => 'Admin',
            'password' => Hash::make('password'),
        ]);

        // 3. Super Admin sebagai peserta
        Peserta::create([
            'badge_no' => 'SA001',
            'employee_name' => $this->faker->name,
            'dept' => 'IT',
            'position' => 'Head of IT',
            'join_date' => now()->subYears(5),
            'status' => 'Active',
            'gender' => 'Male',
            'category_level' => 'Level 4',
            'user_id' => $superAdmin->id,         // Bisa juga null kalau self-created
            'user_id_login' => $superAdmin->id,   // login pakai akun ini
        ]);

        // 4. Admin sebagai peserta
        Peserta::create([
            'badge_no' => 'AD001',
            'employee_name' => 'Admin HR',
            'dept' => 'HR',
            'position' => 'HR Supervisor',
            'join_date' => now()->subYears(4),
            'status' => 'Active',
            'gender' => 'Female',
            'category_level' => 'Level 3',
            'user_id' => $superAdmin->id,
            'user_id_login' => $admin->id,
        ]);

        // 5. Peserta 1 dan 2 (akun login)
        for ($i = 1; $i <= 2; $i++) {
            $user = User::create([
                'user' => 'peserta' . $i,
                'role' => 'User',
                'password' => Hash::make('password'),
            ]);

            Peserta::create([
                'badge_no' => 'B00' . $i,
                'employee_name' => 'Peserta ' . $i,
                'dept' => 'Production',
                'position' => 'Operator',
                'join_date' => now()->subYears(1),
                'status' => 'Active',
                'gender' => $i % 2 == 0 ? 'Female' : 'Male',
                'category_level' => 'Level 1',
                'user_id' => $admin->id,
                'user_id_login' => $user->id,
            ]);
        }

        // 6. Peserta tanpa akun login
        Peserta::create([
            'badge_no' => 'B003',
            'employee_name' => 'Peserta Tanpa Akun',
            'dept' => 'Quality',
            'position' => 'Inspector',
            'join_date' => now()->subYears(2),
            'status' => 'Active',
            'gender' => 'Female',
            'category_level' => 'Level 2',
            'user_id' => $admin->id,
            'user_id_login' => null,
        ]);
    }
}
