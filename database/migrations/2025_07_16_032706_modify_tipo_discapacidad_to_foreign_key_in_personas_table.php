<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('tipo_discapacidad_id')->nullable()->after('discapacidad');
            $table->foreign('tipo_discapacidad_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('tipo_discapacidad');
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('tipo_discapacidad')->nullable()->after('discapacidad');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['tipo_discapacidad_id']);
            $table->dropColumn('tipo_discapacidad_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'TIPO DE DISCAPACIDAD')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->whereNotNull('tipo_discapacidad')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $tipoDiscapacidad = $persona->tipo_discapacidad;
                $tipoDiscapacidadId = $parametros->search($tipoDiscapacidad) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['tipo_discapacidad_id' => $tipoDiscapacidadId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('tipo_discapacidad_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->tipo_discapacidad_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['tipo_discapacidad' => $parametro->name]);
                }
            }
        });
    }
};
