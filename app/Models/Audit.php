<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Project;
use App\Models\ProjectPage;

class Audit extends Model
{
    use HasFactory;

    // In columns ko mass assignment ke liye allow karein
    protected $fillable = [
        'project_id', 'type', 'competitor_id', 'status',
        'overall_health_score', 'score_tech', 'score_structure', 'score_content',
        'pages_scanned', 'critical_issues',
        'tech_meta_data', 'structure_data', 'content_data', 'summary_data'
    ];

    /**
     * Relationship: Audit aik project se jurra hota hai
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
        public function projectPages()
    {
        return $this->hasMany(ProjectPage::class);
    }
}