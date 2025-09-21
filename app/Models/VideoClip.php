<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoClip extends Model
{
    protected $guarded = [];

    /**
     * Get the user that owns the video clip.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stream that this video clip belongs to.
     *
     * @return BelongsTo
     */
    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }
}
