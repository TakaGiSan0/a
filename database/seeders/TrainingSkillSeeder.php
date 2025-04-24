<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\training_skill;
use Illuminate\Support\Facades\DB;

class TrainingSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('training_skill')->insert([
            ['skill_code' => 'V1','job_skill' => 'Pending',],
            ['skill_code' => 'V2','job_skill' => 'Completed'],
            ['skill_code' => 'V3','job_skill' => 'Waiting Approval'],
        ]);
    }
}
