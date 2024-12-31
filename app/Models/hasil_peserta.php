<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hasil_peserta extends Model
{
    use HasFactory;

    protected $table = 'hasil_peserta';

    protected $fillable = ['peserta_id','training_record_id', 'level', 'practical_result', 'theory_result', 'final_judgement','license'];

    public function trainingrecord()
    {
        return $this->belongsTo(training_record::class, 'training_record_id');
    }
    public function pesertas()
    {
        return $this->belongsTo(peserta::class, 'peserta_id');
    }
}
