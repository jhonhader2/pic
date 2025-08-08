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
        Schema::create('familias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo')->unique();
            $table->string('direccion');
            $table->foreignId('barrio_id')->nullable()->constrained('parametros')->nullOnDelete();
            $table->uuid('jefe_persona_id')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('jefe_persona_id')->references('id')->on('personas')->nullOnDelete();
            $table->index(['barrio_id']);
        });

        Schema::create('familia_personas', function (Blueprint $table) {
            $table->id();
            $table->uuid('familia_id');
            $table->uuid('persona_id');
            $table->string('rol', 100)->nullable();
            $table->boolean('es_jefe')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->foreign('familia_id')->references('id')->on('familias')->cascadeOnDelete();
            $table->foreign('persona_id')->references('id')->on('personas')->cascadeOnDelete();
            $table->unique(['familia_id', 'persona_id']);
            $table->index(['familia_id']);
            $table->index(['persona_id']);
        });

        Schema::create('encuesta_familias', function (Blueprint $table) {
            $table->id();
            $table->uuid('encuesta_id');
            $table->uuid('familia_id');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->cascadeOnDelete();
            $table->foreign('familia_id')->references('id')->on('familias')->cascadeOnDelete();
            $table->unique(['encuesta_id', 'familia_id']);
            $table->index(['encuesta_id']);
            $table->index(['familia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuesta_familias');
        Schema::dropIfExists('familia_personas');
        Schema::dropIfExists('familias');
    }
};
