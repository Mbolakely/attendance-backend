<?php

namespace Database\Seeders;

use App\Models\Parcours;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParcoursSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parcours = [
            [
                'libelle' => 'Génie Logiciel et base de données',
                'code_parcours' => 'GB',
            ],
            [
                'libelle' => 'Administration systeme et reseaux',
                'code_parcours' => 'ASR',
            ],
            [
                'libelle' => 'Informatique generale',
                'code_parcours' => 'IG',
            ],
            [
                'libelle' => 'Intelligence Artificielle',
                'code_parcours' => 'IA',
            ],
        ];

        Parcours::insert($parcours);
    }
}
