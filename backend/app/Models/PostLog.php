<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLog extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
        ];
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
