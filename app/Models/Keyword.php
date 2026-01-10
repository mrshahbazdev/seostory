<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = [
        'term',
        'volume',
        'difficulty',
        'cpc',
        'results'
    ];

    protected $casts = [
        'results' => 'array',
        'cpc' => 'decimal:2',
    ];
}
