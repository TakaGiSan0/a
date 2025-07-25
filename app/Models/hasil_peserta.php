<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class hasil_peserta extends Model
{
    use HasFactory;

    protected $table = 'hasil_peserta';

    protected $fillable = ['peserta_id', 'training_record_id', 'level', 'practical_result', 'theory_result', 'final_judgement', 'license', 'evaluation', 'certificate', 'expired_date', 'category'];

    public function trainingrecord()
    {
        return $this->belongsTo(training_record::class, 'training_record_id');
    }
    public function pesertas()
    {
        return $this->belongsTo(peserta::class, 'peserta_id');
    }

    public function evaluationDetail()
    {
        return $this->hasOne(TrainingEvaluation::class);
    }

    public function getExpiredDateAttribute()
    {
        return $this->attributes['expired_date']
            ? Carbon::parse($this->attributes['expired_date'])->format('d F Y')
            : null;
    }


    public function getStatusAttribute()
{
    $expired = $this->getRawOriginal('expired_date');
    $certificate = $this->certificate;
    $category = $this->category;

    if (empty($expired) && !empty($certificate) && !empty($category)) {
        return 'Active';
    }

    if (!empty($expired) && !empty($certificate) && !empty($category)) {
        return Carbon::parse($expired)->isPast() && !Carbon::parse($expired)->isToday()
            ? 'Non Active'
            : 'Active';
    }

    // Selain itu, anggap Non Active
    return 'Non Active';
}

    public function scopeByUserRole($query, $user)
    {
        if ($user->role === 'Super Admin' || $user->role === 'Admin') {
            return $query;
        } elseif ($user->role === 'User') {
            $dept = $user->pesertaLogin->dept;
            return $query->whereHas('pesertas', function ($q) use ($dept) {
                $q->where('dept', $dept);
            });
        }

        return $query->where('id', null); // Jika bukan Super Admin/Admin/User, kosongkan data
    }
}
