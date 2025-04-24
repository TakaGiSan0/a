<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class training_skill extends Model
{

    use HasFactory;
    protected $table = "training_skill";
    
    protected $fillable = [
        'id',
        'job_skill',
        'skill_code',
    ];

    public function training_records()
    {
        return $this->belongsToMany(Training_Record::class);
    }
}
