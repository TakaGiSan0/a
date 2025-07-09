<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product_code extends Model
{
    use HasFactory;

    protected $table = 'prodcut_code';

    protected $fillable = ['product_code', 'product_name', 'status'];

}