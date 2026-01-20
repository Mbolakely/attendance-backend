<?php

namespace App\Jobs;

use App\Models\Sceance;
use App\Models\Attendance;
use App\Notifications\AttendanceStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAttendanceEmailsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Sceance $sceance) {}

    public function handle(): void
    {
        $attendances = Attendance::with('student')
            ->where('sceance_id', $this->sceance->id)
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->student
                ->notify(new AttendanceStatusNotification(
                    $this->sceance,
                    $attendance
                ));
        }
    }
    // public function __construct(public int $sceanceId) {}

    // public function handle()
    // {
    //     $sceance = Sceance::with('classe.students')->findOrFail($this->sceanceId);

    //     foreach ($sceance->attendances as $attendance) {
    //          $attendance->student
    //             ->notify(new AttendanceStatusNotification(
    //                 $sceance->id,
    //                 $attendance->id
    //             ));
    //     }
    // }
}
