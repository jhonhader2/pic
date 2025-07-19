<?php

namespace Database\Seeders;

use App\Models\Encuesta;
use App\Models\Tema;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EncuestaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📊 Creando encuesta de prueba de salud pública...');

        // Obtener el usuario admin
        $admin = User::where('email', 'admin@pic.com')->first();

        if (!$admin) {
            $this->command->error('❌ No se encontró el usuario admin. Ejecute primero el DatabaseSeeder.');
            return;
        }

        // Crear la encuesta
        $encuesta = $this->crearEncuesta($admin);

        // Asignar temas/preguntas a la encuesta
        $this->asignarTemas($encuesta);

        // Asignar algunas personas a la encuesta
        $this->asignarPersonas($encuesta);

        $this->command->info('✅ Encuesta de prueba creada exitosamente!');
        $this->command->info("   📋 Título: {$encuesta->titulo}");
        $this->command->info("   📅 Período: {$encuesta->fecha_inicio->format('d/m/Y')} - {$encuesta->fecha_fin->format('d/m/Y')}");
        $this->command->info("   🎯 Preguntas asignadas: {$encuesta->temas()->count()}");
        $this->command->info("   👥 Personas asignadas: {$encuesta->personas()->count()}");
    }

    /**
     * Crear la encuesta de prueba
     */
    private function crearEncuesta(User $admin): Encuesta
    {
        $fechaInicio = Carbon::today();
        $fechaFin = Carbon::today()->addMonths(3); // 3 meses de duración

        $encuesta = Encuesta::create([
            'titulo' => 'ENCUESTA DE SALUD PÚBLICA COMUNITARIA 2024',
            'descripcion' => 'Esta encuesta tiene como objetivo evaluar el estado de salud general de la comunidad, hábitos de vida saludable, satisfacción con los servicios de salud y accesibilidad a los mismos. Los resultados serán utilizados para mejorar la calidad de atención y desarrollar programas de promoción de la salud.',
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'activa' => true,
            'created_by' => $admin->id,
        ]);

        $this->command->info("   ✅ Encuesta creada con ID: {$encuesta->id}");

        return $encuesta;
    }

    /**
     * Asignar temas/preguntas a la encuesta
     */
    private function asignarTemas(Encuesta $encuesta): void
    {
        // Buscar las 5 preguntas de salud pública que creamos
        $temasEncuesta = [
            '¿CON QUÉ FRECUENCIA REALIZA ACTIVIDAD FÍSICA?',
            '¿CÓMO CALIFICARÍA SU NIVEL DE SATISFACCIÓN CON LOS SERVICIOS DE SALUD?',
            '¿CON QUÉ FRECUENCIA CONSUME FRUTAS Y VERDURAS?',
            '¿CÓMO CALIFICARÍA SU ESTADO DE SALUD GENERAL?',
            '¿QUÉ TAN FÁCIL ES ACCEDER A LOS SERVICIOS DE SALUD EN SU COMUNIDAD?'
        ];

        $temas = Tema::whereIn('name', $temasEncuesta)->get();

        if ($temas->isEmpty()) {
            $this->command->warn('⚠️  No se encontraron temas de encuesta. Ejecute primero el EncuestaTemaSeeder.');
            return;
        }

        // Asignar temas a la encuesta
        $encuesta->temas()->attach($temas->pluck('id')->toArray());

        $this->command->info("   📝 Asignadas {$temas->count()} preguntas a la encuesta:");
        foreach ($temas as $tema) {
            $this->command->info("      • {$tema->name}");
        }
    }

    /**
     * Asignar personas a la encuesta
     */
    private function asignarPersonas(Encuesta $encuesta): void
    {
        // Obtener algunas personas de prueba (máximo 10)
        $personas = Persona::inRandomOrder()->limit(10)->get();

        if ($personas->isEmpty()) {
            $this->command->warn('⚠️  No se encontraron personas. Ejecute primero el UserPersonaSeeder.');
            return;
        }

        // Preparar datos para la relación many-to-many
        $personasData = [];
        foreach ($personas as $persona) {
            $personasData[$persona->id] = [
                'created_by' => $encuesta->created_by
            ];
        }

        // Asignar personas a la encuesta
        $encuesta->personas()->attach($personasData);

        $this->command->info("   👥 Asignadas {$personas->count()} personas a la encuesta:");
        foreach ($personas->take(5) as $persona) {
            $this->command->info("      • {$persona->nombres} {$persona->apellidos}");
        }

        if ($personas->count() > 5) {
            $this->command->info("      ... y " . ($personas->count() - 5) . " más");
        }
    }
}
