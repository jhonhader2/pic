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
        Schema::table('encuesta_temas', function (Blueprint $table) {
            $table->enum('tipo_pregunta', [
                'seleccion_unica',
                'seleccion_multiple',
                'texto_corto',
                'texto_largo',
                'numero',
                'fecha',
                'escala',
                'archivo'
            ])->default('seleccion_unica')->after('tema_id');

            $table->boolean('requerida')->default(true)->after('tipo_pregunta');
            $table->text('descripcion_pregunta')->nullable()->after('requerida');
            $table->json('opciones_personalizadas')->nullable()->after('descripcion_pregunta');
            $table->integer('orden')->default(0)->after('opciones_personalizadas');

            $table->index('tipo_pregunta');
            $table->index('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuesta_temas', function (Blueprint $table) {
            $table->dropIndex(['tipo_pregunta']);
            $table->dropIndex(['orden']);

            $table->dropColumn([
                'tipo_pregunta',
                'requerida',
                'descripcion_pregunta',
                'opciones_personalizadas',
                'orden'
            ]);
        });
    }
};
