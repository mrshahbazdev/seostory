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
        
        // AI se score nikalne tak hum static score dete hain
        return !empty($this->metadata['analysis']) ? rand(75, 95) : 10;
    }
}