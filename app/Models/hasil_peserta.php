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
        $originalExpiredDate = $this->getRawOriginal('expired_date'); // Ambil nilai asli dari database
        if (empty($originalExpiredDate)) {
            return 'Non Active';
        }
        return Carbon::parse($originalExpiredDate)->isPast() && !Carbon::parse($originalExpiredDate)->isToday() ? 'Non Active' : 'Active';
    }

    public function scopeByUserRole($query, $user)
    {
        if ($user->role === 'Super Admin') {
            return $query; // Super Admin bisa melihat semua data
        } elseif ($user->role === 'Admin' || $user->role === 'User') {
            return $query->whereHas('pesertas', function ($q) use ($user) {
                $q->where('dept', $user->dept); // Filter berdasarkan departemen dari user yang membuat training_record
            });
        }

        return $query->where('id', null); // Jika bukan Super Admin/Admin/User, kosongkan data
    }
}
