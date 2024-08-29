<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class training_record extends Model
{
    use HasFactory;

    protected $table = 'training_records';

    protected $fillable = [
        'doc_ref',
        'theory_result',
        'practical_result',
        'level',
        'final_judgement',
        'category_id',
        'peserta_id',
        'license',
        'training_name',
        'job_skill',
        'trainer_name',
        'rev',
        'skill_code',
        'training_date',
    ];

    public function trainingCategory()
    {
        return $this->belongsTo(category::class, 'category_id');
    }


    public function peserta()
    {
        return $this->belongsTo(peserta::class, 'peserta_id');
    }

}
