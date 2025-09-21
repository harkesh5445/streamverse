<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreamSignalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    private $streamUuid;
    private $senderId;

    public function __construct($data, $streamUuid, $senderId)
    {
        $this->data = $data;
        $this->streamUuid = $streamUuid;
        $this->senderId = $senderId;
    }
    public function broadcastWith()
    {
        return [
            'data' => $this->data,
            'sender_id' => $this->senderId
        ];
    }
    public function broadcastOn()
    {
        return new PrivateChannel('stream.'.$this->streamUuid);
    }
}