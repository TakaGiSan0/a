<?php

namespace Database\Factories;

use App\Models\Peserta;
use Illuminate\Database\Eloquent\Factories\Factory;


class PesertaFactory extends Factory
{
    protected $model = Peserta::class;

    public function definition()
    {
        return [
            'badge_no' => $this->faker->regexify('(P|C)-[0-9]{4}-[0-9]{2}'),
            'employee_name' => $this->faker->name,
            'dept' => $this->faker->randomElement(['Purchasing', 'Spray Paint', 'Molding 1']),
            'position' => $this->faker->randomElement(['Purchasing Senior Officer', 'Senior Supervisor', 'Production Molding Manager']),
            'created_at' => now(),
            'join_date' => $this->faker->date,
            'status' => $this->faker->randomElement(['Active', 'Non Active']),
            'category_level' => $this->faker->randomElement(['Contractor', 'Permanent']),
        ];
    }
}