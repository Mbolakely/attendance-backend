<?php

use App\Jobs\EndSceanceJob;
use App\Events\SceanceStarted;
use App\Models\Sceance;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
  protected function schedule(Schedule $schedule)
  {
    $schedule->call(function () {
      $now = now('Indian/Antananarivo');
      $oneMinuteAgo = $now->copy()->subMinute();

      $sceances = Sceance::whereDate('date', $now->toDateString())
        ->whereTime('debut_sceance', '<=', $now->toTimeString())
        ->whereTime('debut_sceance', '>=', $oneMinuteAgo->toTimeString())
        ->get();

      foreach ($sceances as $sceance) {
        // Event START immédiat
        event(new SceanceStarted($sceance->id, $sceance->classe_id));

        // Job END après 5 minutes
        EndSceanceJob::dispatch($sceance->id, $sceance->classe_id)
          ->delay(now()->addMinutes(5));

          Log::info("EndSceanceJob planifié pour Sceance {$sceance->id}");
      }
    })->everyMinute();
  }

  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');
    require base_path('routes/console.php');
  }
}
