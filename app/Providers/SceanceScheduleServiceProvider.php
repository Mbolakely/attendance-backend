<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Sceance;
use App\Jobs\EndSceanceJob;
use App\Events\SceanceStarted;

class SceanceScheduleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {

            $schedule = app(Schedule::class);

            $schedule->call(function () {

                $now = now('Indian/Antananarivo');
                $oneMinuteAgo = $now->copy()->subMinute();

                Log::info('Scheduler tick Laravel 12');

                $sceances = Sceance::whereDate('date', $now->toDateString())
                    ->whereTime('debut_sceance', '>', $oneMinuteAgo->toTimeString())
                    ->whereTime('debut_sceance', '<=', $now->toTimeString())
                    ->get();

                foreach ($sceances as $sceance) {

                    event(new SceanceStarted($sceance->id, $sceance->classe_id));

                    EndSceanceJob::dispatch($sceance->id, $sceance->classe_id)
                        ->delay($now->copy()->addMinutes(5));

                    Log::info("Sceance {$sceance->id} START + END planifié");
                }

            })->everyMinute();
        });
    }
}
