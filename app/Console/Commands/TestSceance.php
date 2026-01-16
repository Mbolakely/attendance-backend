<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sceance;
use App\Events\SceanceStarted;
use App\Events\SceanceEnded;
use App\Jobs\EndSceanceJob;
use Carbon\Carbon;

class TestSceance extends Command
{
    protected $signature = 'test:sceance';
    protected $description = 'Déclenche SceanceStarted et SceanceEnded après 5 min pour test';

    public function handle(): void
    {
        $now = Carbon::now('Indian/Antananarivo');

        $sceances = Sceance::whereDate('date', $now->toDateString())->get();

        foreach ($sceances as $sceance) {
            $debut = Carbon::parse($sceance->debut_sceance, 'Indian/Antananarivo');

            // Si la séance commence maintenant
            if ($now->format('H:i') === $debut->format('H:i')) {

                // Event Started
                event(new SceanceStarted($sceance->id, $sceance->classe_id));
                $this->info("Sceance {$sceance->id} STARTED pour la classe {$sceance->classe_id}");

                // Planifier SceanceEnded dans 5 minutes
                $delay = now()->diffInSeconds($debut->copy()->addMinutes(5));
                EndSceanceJob::dispatch($sceance->id, $sceance->classe_id)->delay($delay);
                $this->info("Sceance {$sceance->id} END sera déclenchée dans 5 minutes");
            }
        }
    }
}
