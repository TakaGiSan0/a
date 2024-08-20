<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class final_judgement extends Model
{
    use HasFactory;

    protected $table = "final_judgements";

    protected $fillable = [
        'name',
    ];
}
