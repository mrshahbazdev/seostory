<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasUuid;

    protected $fillable = ['name', 'url', 'team_id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}