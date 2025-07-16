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
            $table->foreignId('estado_civil_id')->nullable()->after('identidad_genero');

            // Crear la clave foránea
            $table->foreign('estado_civil_id')->references('id')->on('parametros')->onDelete('set null');
        });

        // Migrar datos existentes si los hay
        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la columna antigua
            $table->dropColumn('estado_civil');

            // Hacer la nueva columna requerida
            $table->foreignId('estado_civil_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Agregar la columna antigua
            $table->string('estado_civil')->after('identidad_genero');
        });

        // Migrar datos de vuelta si es necesario
        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['estado_civil_id']);

            // Eliminar la nueva columna
            $table->dropColumn('estado_civil_id');
        });
    }

    /**
     * Migra los datos existentes de estado_civil a estado_civil_id
     */
    private function migrateExistingData(): void
    {
        // Obtener el tema de estado civil
        $tema = \App\Models\Tema::where('name', 'ESTADO CIVIL')->first();
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
                $estadoCivil = $persona->estado_civil;
                // Buscar el ID del parámetro que coincida con el nombre
                $estadoCivilId = $parametros->search($estadoCivil);
                if (!$estadoCivilId) {
                    $estadoCivilId = $fallbackId;
                }

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['estado_civil_id' => $estadoCivilId]);
            }
        });
    }

    /**
     * Revierte los datos de estado_civil_id a estado_civil
     */
    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('estado_civil_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->estado_civil_id);

                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['estado_civil' => $parametro->name]);
                }
            }
        });
    }
};
