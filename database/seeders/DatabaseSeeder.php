<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\NiveauxSeed;
use Database\Seeders\ParcoursSeed;
use Database\Seeders\ClasseSeed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

          $this->call([SceanceSeeder::class,]);
        // Seed des niveaux
        // $this->call(NiveauxSeed::class);

        // // Seed des parcours
        // $this->call(ParcoursSeed::class);

        // // Seed des classes (avec les groupes)
        // $this->call(ClasseSeed::class);

    }
}
