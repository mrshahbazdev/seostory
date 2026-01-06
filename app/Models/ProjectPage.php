<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPage extends Model
{
    protected $fillable = [
        'project_id',
        'audit_id',
        'url',
        'title',
        'word_count',
        'load_time',
        'status',
        'full_audit_data',
        'schema_types',
        'health_score'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}