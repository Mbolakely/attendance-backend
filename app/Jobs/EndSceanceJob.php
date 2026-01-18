<?php

namespace App\Jobs;

use App\Models\Sceance;
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

    public Sceance $sceance;

    public function __construct(Sceance $sceance)
    {
        $this->sceance = $sceance;
    }

    public function handle(): void
    {
        if ($this->sceance->status !== 'en_cours') {
            return;
        }

        // $this->sceance->update([
        //     'status' => 'terminee',
        // ]);

        Log::info("Sceance {$this->sceance->id} END déclenchée");

        event(new SceanceEnded($this->sceance));
    }
}
