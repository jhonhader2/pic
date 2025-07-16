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
            $table->foreignId('tipo_documento_id')->nullable()->after('user_id');

            // Crear la clave foránea
            $table->foreign('tipo_documento_id')->references('id')->on('parametros')->onDelete('set null');
        });

        // Migrar datos existentes si los hay
        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la columna antigua
            $table->dropColumn('tipo_documento');

            // Hacer la nueva columna requerida
            $table->foreignId('tipo_documento_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Agregar la columna antigua
            $table->string('tipo_documento')->after('user_id');
        });

        // Migrar datos de vuelta si es necesario
        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['tipo_documento_id']);

            // Eliminar la nueva columna
            $table->dropColumn('tipo_documento_id');
        });
    }

    /**
     * Migra los datos existentes de tipo_documento a tipo_documento_id
     */
    private function migrateExistingData(): void
    {
        // Obtener el tema de tipo de documento
        $tema = \App\Models\Tema::where('name', 'TIPO DE DOCUMENTO')->first();
        if (!$tema) {
            return;
        }
        // Obtener los parámetros del tema
        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        // Buscar el ID de 'CEDULA DE CIUDADANIA'
        $cedulaId = $parametros->search('CEDULA DE CIUDADANIA');
        if (!$cedulaId) {
            // Si no existe, tomar el primer parámetro como fallback
            $cedulaId = $parametros->keys()->first();
        }
        // Actualizar registros existentes usando chunk para evitar problemas de memoria y requerimiento de orderBy
        DB::table('personas')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $cedulaId) {
            foreach ($personas as $persona) {
                $tipoDocumento = $persona->tipo_documento;
                // Buscar el ID del parámetro que coincida con el nombre
                $parametroId = $parametros->search($tipoDocumento);
                if (!$parametroId) {
                    $parametroId = $cedulaId;
                }
                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['tipo_documento_id' => $parametroId]);
            }
        });
    }

    /**
     * Revierte los datos de tipo_documento_id a tipo_documento
     */
    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('tipo_documento_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->tipo_documento_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['tipo_documento' => $parametro->name]);
                }
            }
        });
    }
};
