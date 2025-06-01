<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;

class ThreadPolicy
{
    public function view(User $user, Thread $thread): bool
    {
        return $thread->participants
                      ->pluck('id')
                      ->contains($user->id);
    }

    public function send(User $user, Thread $thread): bool
    {
        return $this->view($user, $thread);
    }
}
