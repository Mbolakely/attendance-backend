<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Parcours;

class ClasseSeed extends Seeder
{
    public function run(): void
    {
        $annee = '2024-2025';

        // Combinaisons niveau + parcours
        // Chaque combinaison aura 2 groupes
        $classes = [
            ['niveau' => 'L1', 'parcours' => 'IG'],
            ['niveau' => 'L2', 'parcours' => 'IG'],
            ['niveau' => 'L3', 'parcours' => 'IG'],

            ['niveau' => 'L1', 'parcours' => 'GB'],
            ['niveau' => 'L2', 'parcours' => 'GB'],
            ['niveau' => 'L3', 'parcours' => 'GB'],

            ['niveau' => 'M1', 'parcours' => 'IA'],
            ['niveau' => 'M2', 'parcours' => 'IA'],

            ['niveau' => 'L1', 'parcours' => 'ASR'],
            ['niveau' => 'L2', 'parcours' => 'ASR'],
            ['niveau' => 'L3', 'parcours' => 'ASR'],
        ];

        foreach ($classes as $item) {
            $niveau = Niveau::where('code_niveau', $item['niveau'])->firstOrFail();
            $parcours = Parcours::where('code_parcours', $item['parcours'])->firstOrFail();

            // Créer 2 groupes pour chaque combinaison
            for ($i = 1; $i <= 2; $i++) {
                $libelle = $niveau->code_niveau . '-' . $parcours->code_parcours . ' Groupe ' . $i;
                $code_classe = $niveau->code_niveau . '-' . $parcours->code_parcours . '-' . $i;

                Classe::create([
                    'libelle' => $libelle,
                    'code_classe' => $code_classe,
                    'niveau_id' => $niveau->id,
                    'parcours_id' => $parcours->id,
                ]);
            }
        }
    }
}
