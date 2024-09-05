<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the trainingRecords for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingRecords()
    {
        return $this->hasMany(training_record::class, 'category_id');
    }
}
