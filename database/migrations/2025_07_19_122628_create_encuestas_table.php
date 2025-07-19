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
        Schema::create('encuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activa')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['fecha_inicio', 'fecha_fin']);
            $table->index('activa');
        });

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

        Schema::create('respuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encuesta_id');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_respuesta')->nullable();
            $table->timestamps();

            // Foreign key constraint para encuesta_id
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            // Índice para optimizar búsquedas
            $table->index(['encuesta_id', 'usuario_id']);
        });

        Schema::create('detalle_respuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('respuesta_id');
            $table->foreignId('pregunta_id')->constrained('temas')->onDelete('cascade');
            $table->foreignId('parametro_id')->constrained('parametros')->nullable()->onDelete('set null');
            $table->integer('valor_numerico')->nullable();
            $table->date('fecha_respuesta')->nullable();
            $table->text('ruta_archivo')->nullable();
            $table->text('respuesta')->nullable();
            $table->timestamps();

            // Foreign key constraint para respuesta_id
            $table->foreign('respuesta_id')->references('id')->on('respuestas')->onDelete('cascade');

            // Índices para optimizar búsquedas
            $table->index(['respuesta_id', 'pregunta_id']);
            $table->index('parametro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Corregido: eliminar en orden inverso y con nombres correctos
        Schema::dropIfExists('detalle_respuestas');
        Schema::dropIfExists('respuestas');
        Schema::dropIfExists('encuesta_temas');
        Schema::dropIfExists('encuesta_personas');
        Schema::dropIfExists('encuestas');
    }
};
