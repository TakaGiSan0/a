<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peserta extends Model
{
    use HasFactory;

    protected $table = 'pesertas';

    protected $fillable = ['badge_no', 'employee_name', 'dept', 'position'];


    public function peserta()
    {
        return $this->hasMany(hasil_peserta::class, 'peserta_id');
    }

    public function trainingRecords()
    {
        return $this->belongsToMany(Training_Record::class, 'hasil_peserta')
                    ->withPivot('theory_result', 'practical_result', 'level', 'final_judgement', 'license');
    }
}