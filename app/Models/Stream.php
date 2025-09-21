<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stream extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function ($stream) {
            $stream->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videoClips()
    {
        return $this->hasMany(VideoClip::class);
    }

    public function scheduledStreams()
    {
        return $this->hasMany(ScheduledStream::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}