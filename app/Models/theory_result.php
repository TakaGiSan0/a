<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class theory_result extends Model
{
    use HasFactory;

    protected $table = "theory_results";

    protected $fillable = [
        'name',
    ];

    public function trainingRecords()
    {
        return $this->hasMany(training_record::class, 'theory_result_id');
    }
}
