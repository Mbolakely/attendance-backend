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
        Schema::create('attendances', function (Blueprint $table) {
            $table->foreignId('sceance_id')
                ->constrained('sceances')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->enum('status', ['present', 'absent']);
            $table->float('confiance')->nullable();
            $table->string('justificatif')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['sceance_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
