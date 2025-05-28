<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\training_record;
use App\Models\Training_Comment;



class trainingrecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Membuat 10 data Training_Record beserta Training_Comment
        Training_Record::factory(10)
        ->has(Training_Comment::factory()->count(1), 'comments')
        ->create();
    }
}
