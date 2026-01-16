<?php

namespace Database\Seeders;

use App\Models\Niveau;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NiveauxSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         {
        $niveaux = [
            [
                'libelle' => 'Licence 1',
                'code_niveau' => 'L1',
            ],
            [
                'libelle' => 'Licence 2',
                'code_niveau' => 'L2',
            ],
            [
                'libelle' => 'Licence 3',
                'code_niveau' => 'L3',
            ],
            [
                'libelle' => 'Master 1',
                'code_niveau' => 'M1',
            ],
            [
                'libelle' => 'Master 2',
                'code_niveau' => 'M2',
            ],
        ];

        Niveau::insert($niveaux);
    }
    }
}
