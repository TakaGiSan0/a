<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\training_record;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';

    protected $fillable = ['user', 'name', 'role', 'dept', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function trainingRecords()
    {
        return $this->hasMany(training_record::class, 'user_id');
    }

    public function scopeByUserRole($query, $user)
    {
        if ($user->role === 'Super Admin') {
            return $query; // Super Admin bisa melihat semua user
        }
        return $query->whereHas('pesertaLogin', function ($q) use ($user) {
            $q->where('dept', $user->pesertaLogin->dept);
        });
    }

    public function pesertaLogin()
    {
        return $this->hasOne(Peserta::class, 'user_id_login');
    }


}
