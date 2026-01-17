<?php

namespace App\Jobs;

use App\Events\SceanceEnded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EndSceanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $sceanceId;
    public int $classeId;

    public function __construct(int $sceanceId, int $classeId)
    {
        $this->sceanceId = $sceanceId;
        $this->classeId = $classeId;
        
        Log::info("Event SceanceEnded pour la sceance {$this->sceanceId}");
    }

    public function handle(): void
    {
        Log::info("Sceance {$this->sceanceId} END déclenchée pour la classe {$this->classeId}");
        event(new SceanceEnded($this->sceanceId, $this->classeId));
    }
}
