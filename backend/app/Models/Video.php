<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function mediaAssets()
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function postLogs()
    {
        return $this->hasMany(PostLog::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    public function jobStatuses()
    {
        return $this->hasMany(JobStatus::class);
    }
}
