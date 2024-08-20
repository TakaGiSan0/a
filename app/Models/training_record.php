<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class training_record extends Model
{
    use HasFactory;

    protected $table = 'training_records';

    protected $fillable = [
        'Doc_ref',
        'badge_no',
        'employe_name',
        'dept',
        'position',
        'theory_result',
        'practical_result',
        'level',
        'final_judgement',
        'training_category',
        'license',
        'training_name',
        'job_skill',
        'trainer_name',
        'rev',
        'station',
        'skill_code',
        'training_date',
    ];
}
