<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training_Record extends Model
{
    use HasFactory;

    protected $table = 'training_records';

    protected $fillable = [
        'doc_ref',
        'station',
        'category_id',
        'training_name',
        'job_skill',
        'trainer_name',
        'rev',
        'skill_code',
        'training_date',
        'status',
    ];

    public function trainingCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function hasil_peserta()
    {
        return $this->hasMany(Hasil_Peserta::class, 'training_record_id');
    }
    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'hasil_peserta', 'training_record_id', 'peserta_id')
                    ->withPivot('level', 'final_judgement', 'license', 'theory_result', 'practical_result');
    }

}
