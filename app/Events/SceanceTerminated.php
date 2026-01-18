<?php

namespace App\Events;

use App\Models\Sceance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SceanceTerminated implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(public Sceance $sceance) {}

    public function broadcastOn(): Channel
    {
        return new Channel('sceances');
    }

    public function broadcastAs(): string
    {
        return 'sceance.terminated';
    }

    public function broadcastWith(): array
    {
        return [
            'sceance' => $this->sceance->fresh(),
        ];
    }
}
