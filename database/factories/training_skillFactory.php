<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\training_skill;
use App\Models\training_record;



class training_skillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = training_skill::class;

    public function definition(): array
    {
        return [
            "skill_code"=> $this->faker->randomElement(['V1', 'V2', 'V3']),
            "job_skill"=> $this->faker->randomElement(['Completed', 'Pending', 'Waiting Approval']),
        ];
    }
}
