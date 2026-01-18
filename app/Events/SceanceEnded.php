<?php

namespace App\Events;

use App\Models\Sceance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SceanceEnded implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Sceance $sceance;

    public function __construct(Sceance $sceance)
    {
        $this->sceance = $sceance->load('classe');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('sceances');
    }

    public function broadcastAs(): string
    {
        return 'sceance.ended';
    }
}
