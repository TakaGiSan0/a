<?php

namespace Database\Factories;

use App\Models\Training_Record;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class Training_RecordFactory extends Factory
{
    protected $model = Training_Record::class;

    public function definition()
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id,
            'doc_ref' => $this->faker->numerify('DOC-####'),
            'station' => $this->faker->word,
            'date_start' => $this->faker->date,
            'date_end' => $this->faker->date,
            'training_duration' => $this->faker->time,
            'training_name' => $this->faker->name,
            'job_skill' => $this->faker->word,
            'trainer_name' => $this->faker->name,
            'rev' => $this->faker->randomDigitNotNull,
            'skill_code' => $this->faker->bothify('SK-####'),
            'status' => $this->faker->randomElement(['Completed', 'Pending', 'Waiting Approval']),
            'approval' => 'Pending',
            'comment' => $this->faker->sentence,
        ];
    }
}
