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
            'category_id' => Category::factory("id"),
            'doc_ref' => $this->faker->word,
            'station' => $this->faker->word,
            'training_date' => $this->faker->date,
            'training_name' => $this->faker->word,
            'job_skill' => $this->faker->word,
            'trainer_name' => $this->faker->word,
            'rev' => $this->faker->word,
            'skill_code' => $this->faker->word,
            'status' => true,
            

        ];
    }
}