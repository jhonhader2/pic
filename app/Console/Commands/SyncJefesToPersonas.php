<?php

namespace App\Console\Commands;

use App\Models\Familia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncJefesToPersonas extends Command
{
    protected $signature = 'familias:sync-jefes';
    protected $description = 'Sincroniza los jefes de familia a la tabla familia_personas';

    public function handle()
    {
        $this->info('Sincronizando jefes de familia...');

        $familias = Familia::whereNotNull('jefe_persona_id')->get();
        $count = 0;

        foreach ($familias as $familia) {
            // Verificar si el jefe ya está en familia_personas
            $exists = DB::table('familia_personas')
                ->where('familia_id', $familia->id)
                ->where('persona_id', $familia->jefe_persona_id)
                ->exists();

            if (!$exists) {
                DB::table('familia_personas')->insert([
                    'familia_id' => $familia->id,
                    'persona_id' => $familia->jefe_persona_id,
                    'es_jefe' => true,
                    'created_by' => $familia->created_by,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
                $this->line("✓ Agregado jefe a familia {$familia->codigo}");
            }
        }

        $this->info("Sincronización completada. {$count} jefes agregados.");
    }
}
