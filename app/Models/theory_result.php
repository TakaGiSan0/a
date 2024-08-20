<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class theory_result extends Model
{
    use HasFactory;

    protected $table = "theory_results";

    protected $fillable = [
        'name',
    ];
}
