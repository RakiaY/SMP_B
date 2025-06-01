<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    /**
     * @param  Message  $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Which channel the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->message->thread_id);
    }

    /**
     * The data to broadcast.
     */
    public function broadcastWith(): array
    {
        // include the nested user
        return [
            'message' => $this->message->load('user:id,name,avatar'),
        ];
    }
}
