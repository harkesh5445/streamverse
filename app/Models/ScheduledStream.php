<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledStream extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function videoClip()
    {
        return $this->belongsTo(VideoClip::class);
    }
}
