<?php

namespace Database\Factories;

use App\Models\Peserta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesertaFactory extends Factory
{
    protected $model = Peserta::class;

    public function definition()
    {
        // Mapping user_id ke departemen tertentu
        $userDeptMap = [
            1 => 'IT',
            2 => 'TOOL',
            3 => 'ENG',
            4 => 'MOL2',
        ];

        // Ambil user_id secara acak
        $user = \App\Models\User::inRandomOrder()->first();

        return [
            'badge_no' => $this->faker->regexify('(P|C)-[0-9]{4}-[0-9]{2}'),
            'employee_name' => $this->faker->name,
            'dept' => $userDeptMap[$user->id] ?? 'Molding 1', // Gunakan mapping atau default
            'position' => $this->faker->randomElement([
                'Purchasing Senior Officer',
                'Senior Supervisor',
                'Production Molding Manager'
            ]),
            'created_at' => now(),
            'join_date' => $this->faker->date,
            'status' => $this->faker->randomElement(['Active', 'Non Active']),
            'category_level' => $this->faker->randomElement(['Contractor', 'Permanent']),
            'user_id' => $user->id, // Gunakan user_id yang diambil
        ];
    }
}
