<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email');
            $table->string('id_classe');
            $table->string('num_matricule');
            $table->string('prenom')->nullable();
            $table->string('cin')->unique();
            $table->enum('sexe', ['Femme', 'Homme']);
            $table->string('telephone', 10);
            $table->string('adresse')->nullable();
            $table->date('date_naissance')->nullable();
            $table->date('date_inscription')->nullable();
            $table->json('encodage_facial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
