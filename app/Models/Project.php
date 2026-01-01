<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasUuid;

    protected $fillable = ['name', 'url', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}