<?php

namespace App\Events;

use App\Models\Sceance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SceanceStarted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Sceance $sceance;

    public function __construct(Sceance $sceance)
    {
        $this->sceance = $sceance->load('classe');
    }

    public function broadcastOn(): Channel
    {
        // GLOBAL
        return new Channel('sceances');
    }

    public function broadcastAs(): string
    {
        return 'sceance.started';
    }
}
