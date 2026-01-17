<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SceanceEnded implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $sceance_id;
    public $classe_id;

    public function __construct($sceance_id, $classe_id)
    {
        $this->sceance_id = $sceance_id;
        $this->classe_id = $classe_id;
    }

    public function broadcastOn()
    {
        return new Channel('sceances');
    }

    public function broadcastAs()
    {
        return 'sceance.ended';
    }
}
