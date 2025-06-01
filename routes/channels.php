<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Thread;

// This channel is used for broadcasting events related to chat threads
Broadcast::channel('chat.{threadId}', function ($user, $threadId) {
    return Thread::find($threadId)
                 ->participants()
                 ->where('user_id', $user->id)
                 ->exists();
});

// This channel is used for broadcasting notifications to a specific user
Broadcast::channel(
    'notifications.{userId}',
    fn($user, $userId) => (int)$user->id === (int)$userId
);