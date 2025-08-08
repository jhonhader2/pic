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
        Schema::table('familias', function (Blueprint $table) {
            // Índice único para jefe_persona_id (permitiendo null)
            $table->unique('jefe_persona_id', 'familias_jefe_persona_id_unique');
        });

        Schema::table('familia_personas', function (Blueprint $table) {
            // Índice único para persona_id (una persona solo puede estar en una familia)
            $table->unique('persona_id', 'familia_personas_persona_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('familias', function (Blueprint $table) {
            $table->dropUnique('familias_jefe_persona_id_unique');
        });

        Schema::table('familia_personas', function (Blueprint $table) {
            $table->dropUnique('familia_personas_persona_id_unique');
        });
    }
};
