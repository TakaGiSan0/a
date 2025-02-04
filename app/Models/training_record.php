<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class training_record extends Model
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
        'date_start',
        'date_end',
        'status',
        'approval',
        'comment',
        'attachment',
        'user_id',
    ];

    public function trainingCategory()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function hasil_peserta()
    {
        return $this->hasMany(hasil_peserta::class, 'training_record_id');
    }
    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'hasil_peserta')
                    ->withPivot('level', 'final_judgement', 'license', 'theory_result', 'practical_result');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDateRangeAttribute()
    {
        $start = Carbon::parse($this->date_start)->format('d');
        $end = Carbon::parse($this->date_end)->format('d F Y');

        return "{$start} - {$end}";
    }


}
