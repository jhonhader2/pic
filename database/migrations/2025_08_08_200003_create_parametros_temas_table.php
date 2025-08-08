<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla pivote parametros_temas.
     * 
     * Responsabilidad: Gestionar la relación many-to-many entre
     * parámetros y temas, permitiendo que un parámetro pertenezca
     * a múltiples temas si es necesario.
     */
    public function up(): void
    {
        Schema::create('parametros_temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parametro_id')->constrained('parametros')->onDelete('cascade');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->foreignId('user_create_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['parametro_id', 'tema_id']);
            $table->index('status');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_temas');
    }
};
