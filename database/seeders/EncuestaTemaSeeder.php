<?php

namespace Database\Seeders;

use App\Models\Tema;
use App\Models\Parametro;
use Illuminate\Database\Seeder;

class EncuestaTemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Verificando y creando parámetros para preguntas de encuesta...');
        $this->crearParametrosEncuesta();

        $this->command->info('📝 Creando 5 preguntas de prueba para encuestas...');
        $this->crearPreguntasEncuesta();

        $this->command->info('✅ Seeder de temas de encuesta completado exitosamente!');
    }

    /**
     * Crear parámetros específicos para las preguntas de encuesta
     */
    private function crearParametrosEncuesta(): void
    {
        // Solo crear parámetros específicos que no existen en el ParametroSeeder
        $parametrosEspecificos = [
            // Parámetros para frecuencia de actividad física (IDs 200-203)
            ['id' => 200, 'name' => 'NUNCA REALIZA ACTIVIDAD FÍSICA', 'status' => 1],
            ['id' => 201, 'name' => '1-2 VECES POR SEMANA', 'status' => 1],
            ['id' => 202, 'name' => '3-4 VECES POR SEMANA', 'status' => 1],
            ['id' => 203, 'name' => '5 O MÁS VECES POR SEMANA', 'status' => 1],

            // Parámetros para nivel de satisfacción (IDs 204-208)
            ['id' => 204, 'name' => 'MUY INSATISFECHO', 'status' => 1],
            ['id' => 205, 'name' => 'INSATISFECHO', 'status' => 1],
            ['id' => 206, 'name' => 'NEUTRO', 'status' => 1],
            ['id' => 207, 'name' => 'SATISFECHO', 'status' => 1],
            ['id' => 208, 'name' => 'MUY SATISFECHO', 'status' => 1],

            // Parámetros para frecuencia de consumo (IDs 209-213)
            ['id' => 209, 'name' => 'DIARIAMENTE', 'status' => 1],
            ['id' => 210, 'name' => '2-3 VECES POR SEMANA', 'status' => 1],
            ['id' => 211, 'name' => '1 VEZ POR SEMANA', 'status' => 1],
            ['id' => 212, 'name' => 'OCASIONALMENTE', 'status' => 1],
            ['id' => 213, 'name' => 'NUNCA CONSUME', 'status' => 1],

            // Parámetros para estado de salud (IDs 214-218)
            ['id' => 214, 'name' => 'EXCELENTE', 'status' => 1],
            ['id' => 215, 'name' => 'MUY BUENO', 'status' => 1],
            ['id' => 216, 'name' => 'BUENO', 'status' => 1],
            ['id' => 217, 'name' => 'REGULAR', 'status' => 1],
            ['id' => 218, 'name' => 'MALO', 'status' => 1],

            // Parámetros para acceso a servicios (IDs 219-223)
            ['id' => 219, 'name' => 'MUY FÁCIL', 'status' => 1],
            ['id' => 220, 'name' => 'FÁCIL', 'status' => 1],
            ['id' => 221, 'name' => 'REGULAR', 'status' => 1],
            ['id' => 222, 'name' => 'DIFÍCIL', 'status' => 1],
            ['id' => 223, 'name' => 'MUY DIFÍCIL', 'status' => 1],
        ];

        $creados = 0;
        foreach ($parametrosEspecificos as $parametro) {
            // Verificar si el parámetro ya existe por nombre
            $existente = Parametro::where('name', $parametro['name'])->first();

            if (!$existente) {
                // Solo crear si no existe
                try {
                    $nuevo = Parametro::create([
                        'id' => $parametro['id'],
                        'name' => $parametro['name'],
                        'status' => $parametro['status'],
                        'user_create_id' => 1,
                        'user_edit_id' => 1,
                    ]);
                    $creados++;
                } catch (\Exception $e) {
                    // Si hay error de ID duplicado, usar auto-increment
                    try {
                        $nuevo = Parametro::create([
                            'name' => $parametro['name'],
                            'status' => $parametro['status'],
                            'user_create_id' => 1,
                            'user_edit_id' => 1,
                        ]);
                        $creados++;
                    } catch (\Exception $e2) {
                        $this->command->warn("   ⚠️  No se pudo crear parámetro: {$parametro['name']}");
                    }
                }
            }
        }

        if ($creados > 0) {
            $this->command->info("   ✅ Creados {$creados} nuevos parámetros");
        } else {
            $this->command->info("   ℹ️  Todos los parámetros ya existían");
        }
    }

    /**
     * Crear 5 preguntas de prueba para encuestas
     */
    private function crearPreguntasEncuesta(): void
    {
        $preguntas = [
            [
                'name' => '¿CON QUÉ FRECUENCIA REALIZA ACTIVIDAD FÍSICA?',
                'parametros' => ['NUNCA REALIZA ACTIVIDAD FÍSICA', '1-2 VECES POR SEMANA', '3-4 VECES POR SEMANA', '5 O MÁS VECES POR SEMANA'],
                'descripcion' => 'Pregunta sobre hábitos de ejercicio físico'
            ],
            [
                'name' => '¿CÓMO CALIFICARÍA SU NIVEL DE SATISFACCIÓN CON LOS SERVICIOS DE SALUD?',
                'parametros' => ['MUY INSATISFECHO', 'INSATISFECHO', 'NEUTRO', 'SATISFECHO', 'MUY SATISFECHO'],
                'descripcion' => 'Evaluación de la calidad de atención en salud'
            ],
            [
                'name' => '¿CON QUÉ FRECUENCIA CONSUME FRUTAS Y VERDURAS?',
                'parametros' => ['DIARIAMENTE', '2-3 VECES POR SEMANA', '1 VEZ POR SEMANA', 'OCASIONALMENTE', 'NUNCA CONSUME'],
                'descripcion' => 'Hábitos alimentarios saludables'
            ],
            [
                'name' => '¿CÓMO CALIFICARÍA SU ESTADO DE SALUD GENERAL?',
                'parametros' => ['EXCELENTE', 'MUY BUENO', 'BUENO', 'REGULAR', 'MALO'],
                'descripcion' => 'Autoevaluación del estado de salud'
            ],
            [
                'name' => '¿QUÉ TAN FÁCIL ES ACCEDER A LOS SERVICIOS DE SALUD EN SU COMUNIDAD?',
                'parametros' => ['MUY FÁCIL', 'FÁCIL', 'REGULAR', 'DIFÍCIL', 'MUY DIFÍCIL'],
                'descripcion' => 'Accesibilidad a servicios de salud'
            ]
        ];

        $creadas = 0;
        foreach ($preguntas as $pregunta) {
            $nuevo = $this->crearTemaConParametros($pregunta['name'], $pregunta['parametros']);
            if ($nuevo) {
                $creadas++;
                $this->command->info("   ✅ Creada pregunta: {$pregunta['name']}");
            }
        }

        $this->command->info("   📊 Total de preguntas creadas: {$creadas}/5");
    }

    /**
     * Crea un tema y sincroniza sus parámetros
     * 
     * @return bool True si se creó un nuevo tema, false si ya existía
     */
    private function crearTemaConParametros(string $nombre, array $nombresParametros): bool
    {
        // Verificar si el tema ya existe
        $tema = Tema::firstOrCreate(
            ['name' => $nombre],
            [
                'status' => 1,
                'user_create_id' => 1,
                'user_edit_id' => 1,
            ]
        );

        // Obtener los IDs de los parámetros por nombre
        $parametrosIds = Parametro::whereIn('name', $nombresParametros)->pluck('id')->toArray();

        if (!empty($parametrosIds)) {
            $this->sincronizarParametros($tema, $parametrosIds);
        }

        return $tema->wasRecentlyCreated;
    }

    /**
     * Sincroniza parámetros con un tema
     */
    private function sincronizarParametros(Tema $tema, array $parametrosIds): void
    {
        $syncData = [];
        foreach ($parametrosIds as $id) {
            $syncData[$id] = [
                'user_create_id' => 1,
                'user_edit_id' => 1,
                'status' => 1
            ];
        }

        $tema->parametros()->sync($syncData);
    }
}
