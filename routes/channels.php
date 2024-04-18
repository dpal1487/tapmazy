<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('create-project', function ($project) {
    return true;
});

Broadcast::channel('status-update', function ($user) {
    return $user;
});


// Broadcast::channel('conversation', function ($user, $conversationId) {
//     // Add your authorization logic here
//     // return true; // or false based on your authorization rules
//     return auth()->check();
// });
