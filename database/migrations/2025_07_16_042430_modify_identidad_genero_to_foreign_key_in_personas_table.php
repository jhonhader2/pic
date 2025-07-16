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
            $table->foreignId('identidad_genero_id')->nullable()->after('sexo_id');

            // Crear la clave foránea
            $table->foreign('identidad_genero_id')->references('id')->on('parametros')->onDelete('set null');
        });

        // Migrar datos existentes si los hay
        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la columna antigua
            $table->dropColumn('identidad_genero');

            // Hacer la nueva columna requerida
            $table->foreignId('identidad_genero_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Agregar la columna antigua
            $table->string('identidad_genero')->after('sexo_id');
        });

        // Migrar datos de vuelta si es necesario
        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['identidad_genero_id']);

            // Eliminar la nueva columna
            $table->dropColumn('identidad_genero_id');
        });
    }

    /**
     * Migra los datos existentes de identidad_genero a identidad_genero_id
     */
    private function migrateExistingData(): void
    {
        // Obtener el tema de identidad de género
        $tema = \App\Models\Tema::where('name', 'IDENTIDAD DE GENERO')->first();
        if (!$tema) {
            return;
        }

        // Obtener los parámetros del tema
        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');

        // Buscar el ID del primer parámetro como fallback
        $fallbackId = $parametros->keys()->first();

        // Actualizar registros existentes usando chunk
        DB::table('personas')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $identidadGenero = $persona->identidad_genero;
                if (!$identidadGenero) {
                    continue; // Saltar si no hay valor
                }

                // Buscar el ID del parámetro que coincida con el nombre
                $identidadGeneroId = $parametros->search(strtoupper($identidadGenero));
                if (!$identidadGeneroId) {
                    $identidadGeneroId = $fallbackId;
                }

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['identidad_genero_id' => $identidadGeneroId]);
            }
        });
    }

    /**
     * Revierte los datos de identidad_genero_id a identidad_genero
     */
    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('identidad_genero_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->identidad_genero_id);

                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['identidad_genero' => $parametro->name]);
                }
            }
        });
    }
};
