<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea las tablas de relaciones para encuestas.
     * 
     * Responsabilidad: Gestionar las relaciones many-to-many entre
     * encuestas y personas, y entre encuestas y temas.
     */
    public function up(): void
    {
        $this->createEncuestaPersonasTable();
        $this->createEncuestaTemasTable();
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuesta_temas');
        Schema::dropIfExists('encuesta_personas');
    }

    /**
     * Crea la tabla pivote encuesta_personas.
     */
    private function createEncuestaPersonasTable(): void
    {
        Schema::create('encuesta_personas', function (Blueprint $table) {
            $table->id();
            $table->uuid('encuesta_id');
            $table->uuid('persona_id');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->index(['encuesta_id', 'persona_id']);
        });
    }

    /**
     * Crea la tabla pivote encuesta_temas.
     */
    private function createEncuestaTemasTable(): void
    {
        Schema::create('encuesta_temas', function (Blueprint $table) {
            $table->id();
            $table->uuid('encuesta_id');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->enum('tipo_pregunta', [
                'seleccion_unica',
                'seleccion_multiple',
                'texto_corto',
                'texto_largo',
                'numero',
                'fecha',
                'escala',
                'archivo'
            ])->default('seleccion_unica');
            $table->boolean('requerida')->default(true);
            $table->text('descripcion_pregunta')->nullable();
            $table->json('opciones_personalizadas')->nullable(); // Para opciones adicionales
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->index(['encuesta_id', 'tema_id']);
            $table->index('tipo_pregunta');
            $table->index('orden');
        });
    }
};
