<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\Competitor;
use App\Models\User;
use App\Models\Audit;
class Project extends Model
{
    use HasUuid;

    protected $fillable = [
        'name', 
        'url', 
        'user_id', 
        'team_id', 
        'verification_token', 
        'is_verified', 
        'verified_at'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            // URL se trailing slash khatam kar dein taake hamesha 'clean' URL save ho
            $project->url = rtrim($project->url, '/');
        });
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    /**
     * Project ke saare Competitors
     */
    public function competitors()
    {
        return $this->hasMany(Competitor::class);
    }
}