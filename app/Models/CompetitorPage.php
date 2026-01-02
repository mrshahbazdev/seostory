<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'url',
        'raw_content',
        'metadata',
        'status'
    ];

    // IMPORTANT: Metadata ko array mein convert karein taake Blade mein use ho sakay
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Relationship: Ek page hamesha ek competitor ka hota hai.
     */
    public function competitor()
    {
        return $this->belongsTo(Competitor::class);
    }
}