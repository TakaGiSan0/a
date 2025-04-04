<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peserta extends Model
{
    use HasFactory;

    protected $table = 'pesertas';

    protected $fillable = ['badge_no', 'employee_name', 'dept', 'position', 'join_date', 'category_level', 'status', 'user_id'];


    public function trainingRecords()
    {
        return $this->belongsToMany(Training_Record::class, 'hasil_peserta')
            ->withPivot('theory_result', 'practical_result', 'level', 'final_judgement', 'license');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relasi ke User
    }

    public function scopeByUserRole($query, $user)
    {
        if ($user->role === 'Super Admin') {
            return $query; // Super Admin bisa melihat semua peserta
        }

        // Admin & User hanya melihat peserta dengan dept yang sama
        return $query->where('dept', $user->dept);
    }

    public function scopeByDept($query)
    {
        if (auth('')->user()->role !== 'Super Admin') {
            return $query->where('dept', auth('')->user()->dept);
        }
        return $query;
    }
}
