<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;

class Competitor extends Model
{
    use HasUuid;
    protected $fillable = ['project_id', 'name', 'website_url', 'metadata'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}