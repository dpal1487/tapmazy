<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $data;
    // private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('conversation');
        // return new PrivateChannel('conversation.' . $this->data->conversation_id);
        // return new PrivateChannel('conversation.' . ($this->data['conversation_id'] ?? null));
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
