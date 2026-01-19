<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Sceance;
use App\Models\Attendance;

class AttendanceStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Sceance $sceance,
        public Attendance $attendance
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusLabel = $this->attendance->status === 'present'
            ? 'PRÉSENT'
            : 'ABSENT';

        return (new MailMessage)
            ->subject("Résultat du pointage – {$this->sceance->matiere}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le pointage pour la séance suivante a été enregistré :")
            ->line("Matière : {$this->sceance->matiere}")
            ->line("Date : {$this->sceance->date}")
            ->line("Horaire : {$this->sceance->debut_sceance} - {$this->sceance->fin_sceance}")
            ->line("Statut : {$statusLabel}")
            ->when(
                $this->attendance->confiance,
                fn ($mail) => $mail->line("Confiance IA : {$this->attendance->confiance}")
            )
            ->line("Si ce statut est incorrect, veuillez fournir un justificatif.")
            ->salutation("Cordialement,");
    }
}
