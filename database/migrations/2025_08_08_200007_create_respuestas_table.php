<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea las tablas del sistema de respuestas.
     * 
     * Responsabilidad: Gestionar la estructura de datos para almacenar
     * las respuestas de las encuestas y sus detalles específicos.
     */
    public function up(): void
    {
        $this->createRespuestasTable();
        $this->createDetalleRespuestasTable();
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_respuestas');
        Schema::dropIfExists('respuestas');
    }

    /**
     * Crea la tabla de respuestas.
     */
    private function createRespuestasTable(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encuesta_id');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_respuesta')->nullable();
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->index(['encuesta_id', 'usuario_id']);
        });
    }

    /**
     * Crea la tabla de detalle de respuestas.
     */
    private function createDetalleRespuestasTable(): void
    {
        Schema::create('detalle_respuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('respuesta_id');
            $table->foreignId('pregunta_id')->constrained('temas')->onDelete('cascade');
            $table->foreignId('parametro_id')->nullable()->constrained('parametros')->onDelete('set null');
            $table->integer('valor_numerico')->nullable();
            $table->date('fecha_respuesta')->nullable();
            $table->text('ruta_archivo')->nullable();
            $table->text('respuesta')->nullable();
            $table->timestamps();

            $table->foreign('respuesta_id')->references('id')->on('respuestas')->onDelete('cascade');
            $table->index(['respuesta_id', 'pregunta_id']);
            $table->index('parametro_id');
        });
    }
};
