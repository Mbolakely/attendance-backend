<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sceance;
use App\Models\Classe;
use Carbon\Carbon;

class SceanceSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupère toutes les classes existantes
        $classes = Classe::all();

        if ($classes->isEmpty()) {
            $this->command->info("Aucune classe trouvée. Veuillez d'abord créer des classes.");
            return;
        }

        // Horaires de test (par exemple 08:00-08:30 et 09:00-09:30)
        $horaires = [
            ['debut' => '08:00:00', 'fin' => '08:30:00', 'salle' => 'A101', 'matiere' => 'Math', 'professeur' => 'Prof A'],
            ['debut' => '09:00:00', 'fin' => '09:30:00', 'salle' => 'B202', 'matiere' => 'Physique', 'professeur' => 'Prof B'],
        ];

        foreach ($classes as $classe) {
            foreach ($horaires as $h) {
                Sceance::create([
                    'classe_id' => $classe->id,
                    'date' => Carbon::today()->toDateString(), 
                    'debut_sceance' => $h['debut'],
                    'fin_sceance' => $h['fin'],
                    'salle' => $h['salle'],
                    'matiere' => $h['matiere'],
                    'professeur' => $h['professeur'],
                ]);
            }
        }

        $this->command->info("Seed des séances de test terminé pour aujourd'hui !");
    }
}
