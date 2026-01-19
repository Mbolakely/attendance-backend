<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sceance;
use App\Events\SceanceStarted;
use App\Jobs\EndSceanceJob;
use Carbon\Carbon;

class TestSceance extends Command
{
    protected $signature = 'test:sceance';
    protected $description = 'Déclenche SceanceStarted et planifie SceanceEnded';

    public function handle(): void
    {
        $now = Carbon::now('Indian/Antananarivo');

        $sceances = Sceance::with('classe')
            ->whereDate('date', $now->toDateString())
            ->get();

        foreach ($sceances as $sceance) {
            $debut = Carbon::parse(
                $sceance->date . ' ' . $sceance->debut_sceance,
                'Indian/Antananarivo'
            );

            if ($now->between($debut, $debut->copy()->addMinute())) {

                if ($sceance->etat === 'en_cours') {
                    continue;
                }

                $sceance->update([
                    'etat' => 'en_cours',
                ]);

                event(new SceanceStarted($sceance));

                $this->info("Sceance {$sceance->id} STARTED");

                EndSceanceJob::dispatch($sceance)
                    ->delay($debut->copy()->addMinutes(5));

                $this->info("Sceance {$sceance->id} END planifiée");
            }
        }
    }
}
