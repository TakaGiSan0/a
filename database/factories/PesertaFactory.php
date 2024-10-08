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
            'badge_no' => $this->faker->name,
            'employee_name' => $this->faker->unique()->safeEmail,
            'dept' => $this->faker->phoneNumber,
            'position' => $this->faker->address,
        ];
    }
}