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
            $table->foreignId('eps_id')->nullable()->after('tipo_afiliacion_salud_id');
            $table->foreign('eps_id')->references('id')->on('parametros')->onDelete('set null');
        });

        $this->migrateExistingData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('eps');
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('eps')->nullable()->after('tipo_afiliacion_salud_id');
        });

        $this->rollbackData();

        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['eps_id']);
            $table->dropColumn('eps_id');
        });
    }

    private function migrateExistingData(): void
    {
        $tema = \App\Models\Tema::where('name', 'EPS')->first();
        if (!$tema) return;

        $parametros = $tema->parametros()->select('parametros.name', 'parametros.id')->pluck('name', 'id');
        $fallbackId = $parametros->keys()->first();

        DB::table('personas')->whereNotNull('eps')->orderBy('id')->chunk(100, function ($personas) use ($parametros, $fallbackId) {
            foreach ($personas as $persona) {
                $eps = $persona->eps;
                $epsId = $parametros->search($eps) ?: $fallbackId;

                DB::table('personas')
                    ->where('id', $persona->id)
                    ->update(['eps_id' => $epsId]);
            }
        });
    }

    private function rollbackData(): void
    {
        DB::table('personas')->whereNotNull('eps_id')->orderBy('id')->chunk(100, function ($personas) {
            foreach ($personas as $persona) {
                $parametro = \App\Models\Parametro::find($persona->eps_id);
                if ($parametro) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update(['eps' => $parametro->name]);
                }
            }
        });
    }
};
