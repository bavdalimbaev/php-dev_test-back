<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users', function ($user) {
    return (int) $user->id;
});
