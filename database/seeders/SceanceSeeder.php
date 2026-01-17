<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SceanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sceance')->insert([
            [
                'classe_id' => 1,
                'date' => Carbon::today(),
                'debut_sceance' => '08:00:00',
                'fin_sceance' => '10:00:00',
                'salle' => 'Salle A1',
                'matiere' => 'Mathématiques',
                'professeur' => 'Rakoto Jean',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classe_id' => 1,
                'date' => Carbon::today(),
                'debut_sceance' => '10:00:00',
                'fin_sceance' => '12:00:00',
                'salle' => 'Salle B2',
                'matiere' => 'Informatique',
                'professeur' => 'Rabe Paul',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classe_id' => 2,
                'date' => Carbon::tomorrow(),
                'debut_sceance' => '14:00:00',
                'fin_sceance' => '16:00:00',
                'salle' => 'Salle C3',
                'matiere' => 'Base de données',
                'professeur' => 'Randria Marie',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
