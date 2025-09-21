<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('stream.{uuid}', function ($user, $uuid) {
    // Optionally, add logic to check if the user can join the stream
    return true;
});
