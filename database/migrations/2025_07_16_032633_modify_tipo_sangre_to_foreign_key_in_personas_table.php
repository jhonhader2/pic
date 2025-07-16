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
            $table->foreignId('tipo_sangre_id')->nullable()->after('celular');
            $table->foreign('tipo_sangre_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('tipo_sangre');
            $table->foreignId('tipo_sangre_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('tipo_sangre')->after('celular');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['tipo_sangre_id']);
            $table->dropColumn('tipo_sangre_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'TIPO DE SANGRE')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $tipoSangre = $persona->tipo_sangre;
                $tipoSangreId = $parametros->search($tipoSangre) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['tipo_sangre_id' => $tipoSangreId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('tipo_sangre_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->tipo_sangre_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['tipo_sangre' => $parametro->name]);
                }
            }
        });
    }
};
