<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEvaluation extends Model
{
    protected $table = 'training_evaluation';

    protected $fillable = [
        'hasil_peserta_id',
        'question_1',
        'question_2',
        'question_3',
        'question_4',
        'question_5',
        'status',
    ];

    public function hasilPeserta()
{
    return $this->belongsTo(Hasil_Peserta::class, 'hasil_peserta_id');
}

   
}
