<?php


namespace Database\Factories;

use App\Models\training_record;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;


class training_recordFactory extends Factory
{
    protected $model = training_record::class;

    public function definition()
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id,
            'doc_ref' => $this->faker->numerify('DOC-####'),
            'station' => $this->faker->word,
            'training_date' => $this->faker->date,
            'training_name' => $this->faker->name,
            'job_skill' => $this->faker->word,
            'trainer_name' => $this->faker->name,
            'rev' => $this->faker->randomDigitNotNull,
            'skill_code' => $this->faker->bothify('SK-####'),
            'status' => $this->faker->randomElement(['Pending']),
            'approval' => $this->faker->randomElement(['Pending']),
            'comment' => $this->faker->sentence


        ];
    }
}
