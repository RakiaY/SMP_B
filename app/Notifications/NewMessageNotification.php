<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\Message;
use App\Models\User;

/**
 * @property User $notifiable   // tell IDE that this dynamic prop will exist
 */
class NewMessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(protected Message $message)
    {
    }

    public function via($notifiable): array
    {
        return ['database','broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'thread_id' => $this->message->thread_id,
            'message'   => $this->message->body,
            'from'      => $this->message->user_id,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'thread_id' => $this->message->thread_id,
            'message'   => $this->message->body,
            'from'      => $this->message->user_id,
        ]);
    }

    /**
     * Must match the parent signature exactly — no parameters.
     */
    public function broadcastOn(): PrivateChannel
    {
        // at runtime Laravel will set $this->notifiable to the User instance
        return new PrivateChannel('notifications.'.$this->notifiable->id);
    }

    /**
     * Also matches the parent (no parameters).
     */
    public function broadcastWith(): array
    {
        return [
            'thread_id' => $this->message->thread_id,
            'message'   => $this->message->body,
            'from'      => $this->message->user_id,
        ];
    }
}
