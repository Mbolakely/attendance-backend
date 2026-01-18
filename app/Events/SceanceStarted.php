<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SceanceStarted implements ShouldBroadcast
{
    use SerializesModels;

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
        return 'sceance.started';
    }
}
