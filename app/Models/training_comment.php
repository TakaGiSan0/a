<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class training_comment extends Model
{

    use HasFactory;
    
    protected $table = "comment_training";

    protected $fillable = [
        'id',
        'comment',
        'approval',
        'training_record_id',
    ];

    public function trainingRecord()
    {
        return $this->belongsTo(Training_Record::class, 'training_record_id');
    }

    
}
