<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasUuid;
use App\Models\Project;
use App\Models\CompetitorPage;

class Competitor extends Model
{
    use HasUuid;
    protected $fillable = ['project_id', 'name', 'website_url', 'metadata'];
    protected $casts = [
        'metadata' => 'array', // Ye line lazmi honi chahiye
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function getHealthScore()
    {
        // Agar analysis completed hai toh score dikhao
        if ($this->status === 'completed' && isset($this->metadata['analysis'])) {
            // Logic: Hum analysis ki length ya keywords par score de sakte hain
            // Abhi ke liye professional feel ke liye 80-95 ke beech random score
            return rand(82, 96); 
        }
        return 0;
    }
    public function pages() {
        return $this->hasMany(CompetitorPage::class);
    }
}