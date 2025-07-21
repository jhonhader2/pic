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
     * Ejecuta los seeders de la base de datos.
     */
    public function run(): void
    {
        $this->command->info('📊 Creando encuesta de prueba de salud pública...');

        $admin = $this->getAdminUser();
        if (!$admin) {
            $this->command->error('❌ No se encontró el usuario admin. Ejecute primero el DatabaseSeeder.');
            return;
        }

        $encuesta = $this->createEncuesta($admin);
        $this->assignTemas($encuesta);
        $this->assignPersonas($encuesta);

        $this->displayEncuestaInfo($encuesta);
    }

    /**
     * Obtiene el usuario administrador.
     */
    private function getAdminUser(): ?User
    {
        return User::where('email', 'admin@pic.com')->first();
    }

    /**
     * Crea la encuesta de prueba.
     */
    private function createEncuesta(User $admin): Encuesta
    {
        $fechaInicio = Carbon::today();
        $fechaFin = Carbon::today()->addMonths(3);

        $encuesta = Encuesta::create([
            'titulo' => 'ENCUESTA DE SALUD PÚBLICA COMUNITARIA 2024',
            'descripcion' => $this->getEncuestaDescription(),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'activa' => true,
            'created_by' => $admin->id,
        ]);

        $this->command->info("   ✅ Encuesta creada con ID: {$encuesta->id}");
        return $encuesta;
    }

    /**
     * Obtiene la descripción de la encuesta.
     */
    private function getEncuestaDescription(): string
    {
        return 'Esta encuesta tiene como objetivo evaluar el estado de salud general de la comunidad, ' .
            'hábitos de vida saludable, satisfacción con los servicios de salud y accesibilidad a los mismos. ' .
            'Los resultados serán utilizados para mejorar la calidad de atención y desarrollar programas ' .
            'de promoción de la salud.';
    }

    /**
     * Asigna temas/preguntas a la encuesta.
     */
    private function assignTemas(Encuesta $encuesta): void
    {
        $temasEncuesta = $this->getTemasEncuesta();
        $temas = Tema::whereIn('name', $temasEncuesta)->get();

        if ($temas->isEmpty()) {
            $this->command->warn('⚠️  No se encontraron temas de encuesta. Ejecute primero el EncuestaTemaSeeder.');
            return;
        }

        $encuesta->temas()->attach($temas->pluck('id')->toArray());
        $this->displayTemasAsignados($temas);
    }

    /**
     * Obtiene los temas de la encuesta.
     */
    private function getTemasEncuesta(): array
    {
        return [
            '¿CON QUÉ FRECUENCIA REALIZA ACTIVIDAD FÍSICA?',
            '¿CÓMO CALIFICARÍA SU NIVEL DE SATISFACCIÓN CON LOS SERVICIOS DE SALUD?',
            '¿CON QUÉ FRECUENCIA CONSUME FRUTAS Y VERDURAS?',
            '¿CÓMO CALIFICARÍA SU ESTADO DE SALUD GENERAL?',
            '¿QUÉ TAN FÁCIL ES ACCEDER A LOS SERVICIOS DE SALUD EN SU COMUNIDAD?'
        ];
    }

    /**
     * Muestra los temas asignados.
     */
    private function displayTemasAsignados($temas): void
    {
        $this->command->info("   📝 Asignadas {$temas->count()} preguntas a la encuesta:");
        foreach ($temas as $tema) {
            $this->command->info("      • {$tema->name}");
        }
    }

    /**
     * Asigna personas a la encuesta.
     */
    private function assignPersonas(Encuesta $encuesta): void
    {
        $personas = Persona::inRandomOrder()->limit(10)->get();

        if ($personas->isEmpty()) {
            $this->command->warn('⚠️  No se encontraron personas. Ejecute primero el UserPersonaSeeder.');
            return;
        }

        $personasData = $this->preparePersonasData($personas, $encuesta->created_by);
        $encuesta->personas()->attach($personasData);
        $this->displayPersonasAsignadas($personas);
    }

    /**
     * Prepara los datos de las personas para la relación.
     */
    private function preparePersonasData($personas, int $createdBy): array
    {
        $personasData = [];
        foreach ($personas as $persona) {
            $personasData[$persona->id] = ['created_by' => $createdBy];
        }
        return $personasData;
    }

    /**
     * Muestra las personas asignadas.
     */
    private function displayPersonasAsignadas($personas): void
    {
        $this->command->info("   👥 Asignadas {$personas->count()} personas a la encuesta:");

        foreach ($personas->take(5) as $persona) {
            $this->command->info("      • {$persona->nombres} {$persona->apellidos}");
        }

        if ($personas->count() > 5) {
            $this->command->info("      ... y " . ($personas->count() - 5) . " más");
        }
    }

    /**
     * Muestra la información final de la encuesta.
     */
    private function displayEncuestaInfo(Encuesta $encuesta): void
    {
        $this->command->info('✅ Encuesta de prueba creada exitosamente!');
        $this->command->info("   📋 Título: {$encuesta->titulo}");
        $this->command->info("   📅 Período: {$encuesta->fecha_inicio->format('d/m/Y')} - {$encuesta->fecha_fin->format('d/m/Y')}");
        $this->command->info("   🎯 Preguntas asignadas: {$encuesta->temas()->count()}");
        $this->command->info("   👥 Personas asignadas: {$encuesta->personas()->count()}");
    }
}
