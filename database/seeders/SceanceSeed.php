<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sceance;
use App\Models\Classe;
use Carbon\Carbon;

class SceanceSeed extends Seeder
{
    public function run(): void
    {
        // Prendre UNE seule classe
        $classe = Classe::first();

        if (!$classe) {
            $this->command->warn("Aucune classe trouvée.");
            return;
        }

        // Nettoyage (optionnel mais recommandé pour tests)
        Sceance::whereDate('date', today())->delete();

        // Heure de départ : maintenant + 2 minutes
        $startTime = now('Indian/Antananarivo')->addMinutes(2);

        for ($i = 0; $i < 5; $i++) {

            $debut = $startTime->copy()->addMinutes($i * 10);
            $fin   = $debut->copy()->addMinutes(30);

            Sceance::create([
                'classe_id'     => $classe->id,
                'date'          => $debut->toDateString(),
                'debut_sceance' => $debut->format('H:i:s'),
                'fin_sceance'   => $fin->format('H:i:s'),
                'salle'         => 'Salle ' . chr(65 + $i), 
                'matiere'       => 'Matière ' . ($i + 1),
                'professeur'    => 'Prof ' . ($i + 1),
                'status'        => 'planifiee'
            ]);

            $this->command->info("Séance " . ($i + 1) . " créée : " . $debut->format('H:i') . " - " . $fin->format('H:i'));
        }

        $this->command->info("5 séances créées pour le test.");
    }
}
