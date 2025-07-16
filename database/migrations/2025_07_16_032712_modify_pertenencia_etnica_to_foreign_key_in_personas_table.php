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
            $table->foreignId('pertenencia_etnica_id')->nullable()->after('atencion_integral_discapacidad');
            $table->foreign('pertenencia_etnica_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('pertenencia_etnica');
            $table->foreignId('pertenencia_etnica_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('pertenencia_etnica')->after('atencion_integral_discapacidad');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['pertenencia_etnica_id']);
            $table->dropColumn('pertenencia_etnica_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'TIPO DE PERTENENCIA ETNICA')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $pertenenciaEtnica = $persona->pertenencia_etnica;
                $pertenenciaEtnicaId = $parametros->search($pertenenciaEtnica) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['pertenencia_etnica_id' => $pertenenciaEtnicaId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('pertenencia_etnica_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->pertenencia_etnica_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['pertenencia_etnica' => $parametro->name]);
                }
            }
        });
    }
};
