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
        // Eliminar las tablas existentes
        Schema::dropIfExists('encuesta_temas');
        Schema::dropIfExists('encuesta_personas');

        // Recrear encuesta_personas con id auto-incremental
        Schema::create('encuesta_personas', function (Blueprint $table) {
            $table->id(); // Cambiar a id auto-incremental
            $table->uuid('encuesta_id');
            $table->uuid('persona_id');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');

            // Índice para optimizar búsquedas
            $table->index(['encuesta_id', 'persona_id']);
        });

        // Recrear encuesta_temas con id auto-incremental
        Schema::create('encuesta_temas', function (Blueprint $table) {
            $table->id(); // Cambiar a id auto-incremental
            $table->uuid('encuesta_id');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->timestamps();

            // Foreign key constraint para encuesta_id
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            // Índice para optimizar búsquedas
            $table->index(['encuesta_id', 'tema_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar las tablas corregidas
        Schema::dropIfExists('encuesta_temas');
        Schema::dropIfExists('encuesta_personas');

        // Recrear encuesta_personas original con UUID
        Schema::create('encuesta_personas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encuesta_id');
            $table->uuid('persona_id');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');

            // Índice para optimizar búsquedas
            $table->index(['encuesta_id', 'persona_id']);
        });

        // Recrear encuesta_temas original con UUID
        Schema::create('encuesta_temas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encuesta_id');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->timestamps();

            // Foreign key constraint para encuesta_id
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            // Índice para optimizar búsquedas
            $table->index(['encuesta_id', 'tema_id']);
        });
    }
};
