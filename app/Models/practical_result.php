<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class practical_result extends Model
{
    use HasFactory;

    protected $table = "practical_results";

    protected $fillable = [
        'name',
    ];
}
