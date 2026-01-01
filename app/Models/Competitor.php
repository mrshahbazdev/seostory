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
    public function getHealthScore()
    {
        if ($this->status !== 'completed') return 0;
        
        // Fill the bar based on analysis presence
        return !empty($this->metadata['analysis']) ? 85 : 10;
    }
}