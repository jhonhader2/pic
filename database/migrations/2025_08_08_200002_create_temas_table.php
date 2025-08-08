<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de temas.
     * 
     * Responsabilidad: Gestionar la estructura de datos para los temas
     * que agrupan parámetros relacionados (ej: SEXO, TIPO_DOCUMENTO).
     */
    public function up(): void
    {
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('descripcion')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('user_create_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('temas');
    }
};
