<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Primero agregar la nueva columna como nullable
            $table->foreignId('sexo_id')->nullable()->after('fecha_nacimiento');

            // Crear la clave foránea
            $table->foreign('sexo_id')->references('id')->on('parametros')->onDelete('set null');
        });

        // Migrar datos existentes si los hay
        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la columna antigua
            $table->dropColumn('sexo');

            // Hacer la nueva columna requerida
            $table->foreignId('sexo_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Agregar la columna antigua
            $table->boolean('sexo')->default(true)->after('fecha_nacimiento');
        });

        // Migrar datos de vuelta si es necesario
        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['sexo_id']);

            // Eliminar la nueva columna
            $table->dropColumn('sexo_id');
        });
    }

    /**
     * Migra los datos existentes de sexo a sexo_id
     */
    private function migrateExistingData(): void
    {
        // Obtener el tema de sexo
        $tema = \App\Models\Tema::where('name', 'SEXO')->first();
        if (!$tema) {
            return;
        }

        // Obtener los parámetros del tema
        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');

        // Buscar los IDs de Masculino y Femenino
        $masculinoId = $parametros->search('MASCULINO');
        $femeninoId = $parametros->search('FEMENINO');

        // Si no existen, usar los primeros parámetros disponibles
        if (!$masculinoId) {
            $masculinoId = $parametros->keys()->first();
        }
        if (!$femeninoId) {
            $femeninoId = $parametros->keys()->last() ?: $masculinoId;
        }

        // Actualizar registros existentes usando chunk
        DB::table('personas')->orderBy('id')->chunk(100, function ($personas) use ($masculinoId, $femeninoId) {
            foreach ($personas as $persona) {
                $sexo = $persona->sexo;
                // Convertir boolean a ID del parámetro
                $sexoId = $sexo ? $masculinoId : $femeninoId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['sexo_id' => $sexoId]);
            }
        });
    }

    /**
     * Revierte los datos de sexo_id a sexo
     */
    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('sexo_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->sexo_id);

                if ($parametro) {
                    // Convertir nombre del parámetro a boolean
                    $sexo = strtoupper($parametro->name) === 'MASCULINO';

                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['sexo' => $sexo]);
                }
            }
        });
    }
};
