<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class level extends Model
{
    use HasFactory;

    protected $table = "levels";

    protected $fillable = [
        'level',
    ];
    public function trainingRecords()
    {
        return $this->hasMany(training_record::class, 'level');
    }
}
