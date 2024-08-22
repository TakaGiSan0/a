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
        'theory_result_id',
        'practical_result_id',
        'level_id',
        'final_judgement_id',
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

    public function final_judgement()
    {
        return $this->belongsTo(final_judgement::class, 'final_judgement_id');
    }
    public function level()
    {
        return $this->belongsTo(level::class, 'level_id');
    }
    public function peserta()
    {
        return $this->belongsTo(peserta::class, 'peserta_id');
    }
    public function practical()
    {
        return $this->belongsTo(practical_result::class, 'practical_result_id');
    }
    public function theory()
    {
        return $this->belongsTo(theory_result::class, 'theory_result_id');
    }
}
