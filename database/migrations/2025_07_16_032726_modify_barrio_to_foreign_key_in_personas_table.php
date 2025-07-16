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
            $table->foreignId('barrio_id')->nullable()->after('ocupacion_id');
            $table->foreign('barrio_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('barrio');
            $table->foreignId('barrio_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('barrio')->after('ocupacion_id');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['barrio_id']);
            $table->dropColumn('barrio_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'ESTRATO')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $barrio = $persona->barrio;
                $barrioId = $parametros->search($barrio) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['barrio_id' => $barrioId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('barrio_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->barrio_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['barrio' => $parametro->name]);
                }
            }
        });
    }
};
