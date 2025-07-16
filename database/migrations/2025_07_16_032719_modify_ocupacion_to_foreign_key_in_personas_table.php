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
            $table->foreignId('ocupacion_id')->nullable()->after('nombre_etnia');
            $table->foreign('ocupacion_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('ocupacion');
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('ocupacion')->nullable()->after('nombre_etnia');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['ocupacion_id']);
            $table->dropColumn('ocupacion_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'OCUPACION')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->whereNotNull('ocupacion')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $ocupacion = $persona->ocupacion;
                $ocupacionId = $parametros->search($ocupacion) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['ocupacion_id' => $ocupacionId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('ocupacion_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->ocupacion_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['ocupacion' => $parametro->name]);
                }
            }
        });
    }
};
