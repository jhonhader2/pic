<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de encuestas.
     * 
     * Responsabilidad: Gestionar la estructura de datos para las encuestas
     * del sistema, incluyendo información básica como título, fechas y estado.
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

            $table->index(['fecha_inicio', 'fecha_fin']);
            $table->index('activa');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};
