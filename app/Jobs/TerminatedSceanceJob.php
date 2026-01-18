<?php

namespace App\Jobs;

use App\Models\Sceance;
use App\Events\SceanceTerminated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TerminatedSceanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $sceanceId) {}

    public function handle(): void
    {
        $sceance = Sceance::find($this->sceanceId);

        if (!$sceance || $sceance->status === 'terminee') {
            return;
        }

        $sceance->update([
            'status' => 'terminee',
        ]);

        Log::info("Sceance {$sceance->id} TERMINÉE");

        event(new SceanceTerminated($sceance));
    }
}
