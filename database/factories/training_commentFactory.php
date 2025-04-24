<?php
namespace Database\Factories;

use App\Models\Training_Comment;
use App\Models\Training_Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class Training_CommentFactory extends Factory
{
    protected $model = Training_Comment::class;

    public function definition()
    {
        return [
            'training_record_id' => Training_Record::factory(), // Menautkan ke TrainingRecord
            'comment' => $this->faker->sentence(),  // Komentar acak
            'approval' => $this->faker->randomElement(['Pending', 'Approved', 'Reject']),  // Approval acak
            
        ];
    }
}
