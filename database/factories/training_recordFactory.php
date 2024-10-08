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
            'doc_ref' => $this->faker->sentence,
            'station' => $this->faker->paragraph,
            'training_date' => $this->faker->date,
            'training_name' => $this->faker->sentence,
            'job_skill' => $this->faker->sentence,
            'trainer_name' => $this->faker->sentence,
            'rev' => $this->faker->sentence,
            'skill_code' => $this->faker->sentence,
            'status' => true,
            

        ];
    }
}