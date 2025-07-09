<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRequest extends Model
{

    use HasFactory;
    protected $table = "training_request";
    
    protected $fillable = [
        'id',
        'user_id_login',
        'peserta_id',
        'description',
    ];
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function scopeByUserRoleRequest($query, $user)
{
    $user = auth("")->user();

    if (!$user) {
        return $query->whereRaw('1 = 0'); 
    }

    if ($user->role === 'Super Admin') {
        return $query; 
    }

    return $query->where('user_id_login', $user->id);
}
    
}
