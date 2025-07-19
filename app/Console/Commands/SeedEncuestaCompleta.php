<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\EncuestaTemaSeeder;
use Database\Seeders\EncuestaSeeder;

class SeedEncuestaCompleta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:encuesta-completa {--force : Forzar la ejecución sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear preguntas de prueba y encuesta completa de salud pública';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('¿Está seguro de que desea crear las preguntas y encuesta de prueba?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('🚀 Iniciando creación completa de encuesta de prueba...');

        try {
            // Paso 1: Crear temas/preguntas
            $this->info('📝 Paso 1: Creando preguntas de prueba...');
            $temaSeeder = new EncuestaTemaSeeder();
            $temaSeeder->setCommand($this);
            $temaSeeder->run();

            $this->newLine();

            // Paso 2: Crear encuesta
            $this->info('📊 Paso 2: Creando encuesta de prueba...');
            $encuestaSeeder = new EncuestaSeeder();
            $encuestaSeeder->setCommand($this);
            $encuestaSeeder->run();

            $this->newLine();
            $this->info('🎉 ¡Proceso completo finalizado exitosamente!');
            $this->info('✅ Preguntas de prueba creadas');
            $this->info('✅ Encuesta de prueba creada');
            $this->info('✅ Personas asignadas a la encuesta');
            $this->info('');
            $this->info('Ahora puede acceder al sistema y ver la encuesta completa.');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error durante el proceso:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
