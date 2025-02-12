<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hasil_Peserta;
use App\Models\Peserta;
use App\Models\Training_Record;

class hasil_pesertaFactory extends Factory
{
    protected $model = Hasil_Peserta::class;

    public function definition(): array
    {
        return [
            'peserta_id' => Peserta::factory(),
            'training_record_id' => Training_Record::factory(),
            'theory_result' => $this->faker->randomElement(['Pass', 'Fail']),
            'practical_result' => $this->faker->randomElement(['Pass', 'Fail']),
            'level' => $this->faker->randomElement(['1', '2', '3', '4']),
            'final_judgement' => $this->faker->randomElement(['Competence', 'Attend']),
            'license' => $this->faker->randomElement(['1', '0']),
        ];
    }
}
