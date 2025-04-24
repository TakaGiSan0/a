<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingSkillRecord extends Model
{
    use HasFactory;
    protected $table = "training_record_training_skill";
    
    protected $fillable = [
        'id',
        'training_record_id',
        'training_skill_id',
    ];

    public function training_skill()
    {
        return $this->belongsTo(Training_Skill::class, 'training_skill_id');
    }
}
