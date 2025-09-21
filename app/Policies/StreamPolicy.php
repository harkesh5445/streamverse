<?php

namespace App\Policies;

use App\Models\Stream;
use App\Models\User;

class StreamPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Allow listing streams if needed
    }

    public function view(User $user, Stream $stream): bool
    {
        // Allow all authenticated users to view any stream
        return true;
        // Or, to allow only the owner:
        // return $user->id === $stream->user_id;
    }

    public function create(User $user): bool
    {
        return true; // Allow authenticated users to create streams
    }

    public function update(User $user, Stream $stream): bool
    {
        return $user->id === $stream->user_id;
    }

    public function delete(User $user, Stream $stream): bool
    {
        return $user->id === $stream->user_id;
    }

    public function restore(User $user, Stream $stream): bool
    {
        return $user->id === $stream->user_id;
    }

    public function forceDelete(User $user, Stream $stream): bool
    {
        return $user->id === $stream->user_id;
    }

    // Add this for broadcasting signals
    public function broadcastSignal(User $user, Stream $stream): bool
    {
        // Allow only the owner, or all users as needed
        return true;
        // Or: return $user->id === $stream->user_id;
    }
}