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
            $table->foreignId('tipo_afiliacion_salud_id')->nullable()->after('afiliacion_salud');
            $table->foreign('tipo_afiliacion_salud_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('tipo_afiliacion_salud');
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('tipo_afiliacion_salud')->nullable()->after('afiliacion_salud');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['tipo_afiliacion_salud_id']);
            $table->dropColumn('tipo_afiliacion_salud_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'TIPO DE AFILIACIÓN EN SALUD')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->whereNotNull('tipo_afiliacion_salud')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $tipoAfiliacion = $persona->tipo_afiliacion_salud;
                $tipoAfiliacionId = $parametros->search($tipoAfiliacion) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['tipo_afiliacion_salud_id' => $tipoAfiliacionId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('tipo_afiliacion_salud_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->tipo_afiliacion_salud_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['tipo_afiliacion_salud' => $parametro->name]);
                }
            }
        });
    }
};
