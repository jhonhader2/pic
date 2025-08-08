<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Persona;
use App\Models\Parametro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de ejemplo con sus personas asociadas
        $usersData = [
            [
                'user' => [
                    'name' => 'Jeimy Acosta',
                    'email' => 'jacosta@pic.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                'persona' => [
                    'tipo_documento_id' => 8, // CEDULA DE CIUDADANIA
                    'numero_documento' => '12345678',
                    'primer_nombre' => 'Jeimy',
                    'segundo_nombre' => '',
                    'primer_apellido' => 'Acosta',
                    'segundo_apellido' => '',
                    'fecha_nacimiento' => '1985-03-15',
                    'sexo_id' => 13, // MASCULINO
                    'identidad_genero_id' => 16, // MUJER
                    'estado_civil_id' => 21, // CASADO
                    'telefono' => '6012345678',
                    'celular' => '3001234567',
                    'tipo_sangre_id' => 27, // O
                    'factor_rh_id' => 28, // POSITIVO
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud_id' => 30, // CONTRIBUTIVO
                    'eps_id' => 34, // NUEVA EPS
                    'discapacidad' => false,
                    'tipo_discapacidad_id' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica_id' => 43, // INDIGENA
                    'nombre_etnia' => null,
                    'ocupacion_id' => 75, // PERSONA EN TRABAJO FORMAL
                    'barrio_id' => 101, // Primero de Mayo
                    'direccion' => 'Calle 15 # 23-45',
                    'foto' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'María Elena Rodríguez',
                    'email' => 'maria.rodriguez@example.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                'persona' => [
                    'tipo_documento_id' => 8, // CEDULA DE CIUDADANIA
                    'numero_documento' => '87654321',
                    'primer_nombre' => 'María Elena',
                    'segundo_nombre' => 'Isabel',
                    'primer_apellido' => 'Rodríguez',
                    'segundo_apellido' => 'López',
                    'fecha_nacimiento' => '1990-07-22',
                    'sexo_id' => 14, // FEMENINO
                    'identidad_genero_id' => 16, // MUJER
                    'estado_civil_id' => 20, // SOLTERO
                    'telefono' => '6018765432',
                    'celular' => '3008765432',
                    'tipo_sangre_id' => 24, // A
                    'factor_rh_id' => 28, // POSITIVO
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud_id' => 30, // CONTRIBUTIVO
                    'eps_id' => 34, // NUEVA EPS
                    'discapacidad' => false,
                    'tipo_discapacidad_id' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica_id' => 43, // INDIGENA
                    'nombre_etnia' => null,
                    'ocupacion_id' => 75, // PERSONA EN TRABAJO FORMAL
                    'barrio_id' => 102, // La Paz
                    'direccion' => 'Carrera 7 # 45-67',
                    'foto' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'Carlos Andrés Morales',
                    'email' => 'carlos.morales@example.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                'persona' => [
                    'tipo_documento_id' => 8, // CEDULA DE CIUDADANIA
                    'numero_documento' => '11223344',
                    'primer_nombre' => 'Carlos Andrés',
                    'segundo_nombre' => null,
                    'primer_apellido' => 'Morales',
                    'segundo_apellido' => 'Hernández',
                    'fecha_nacimiento' => '1988-11-08',
                    'sexo_id' => 13, // MASCULINO
                    'identidad_genero_id' => 15, // HOMBRE
                    'estado_civil_id' => 22, // DIVORCIADO
                    'telefono' => '6011122334',
                    'celular' => '3001122334',
                    'tipo_sangre_id' => 25, // B
                    'factor_rh_id' => 28, // POSITIVO
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud_id' => 31, // SUBSIDIADO
                    'eps_id' => 33, // SISBEN
                    'discapacidad' => true,
                    'tipo_discapacidad_id' => 36, // FISICA
                    'atencion_integral_discapacidad' => true,
                    'pertenencia_etnica_id' => 43, // INDIGENA
                    'nombre_etnia' => null,
                    'ocupacion_id' => 75, // PERSONA EN TRABAJO FORMAL
                    'barrio_id' => 105, // La Esperanza
                    'direccion' => 'Calle 127 # 15-30',
                    'foto' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'Ana Patricia Silva',
                    'email' => 'ana.silva@example.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                'persona' => [
                    'tipo_documento_id' => 8, // CEDULA DE CIUDADANIA
                    'numero_documento' => '55667788',
                    'primer_nombre' => 'Ana Patricia',
                    'segundo_nombre' => 'Carmen',
                    'primer_apellido' => 'Silva',
                    'segundo_apellido' => 'Vargas',
                    'fecha_nacimiento' => '1992-04-12',
                    'sexo_id' => 14, // FEMENINO
                    'identidad_genero_id' => 16, // MUJER
                    'estado_civil_id' => 20, // SOLTERO
                    'telefono' => '6015566778',
                    'celular' => '3005566778',
                    'tipo_sangre_id' => 26, // AB
                    'factor_rh_id' => 28, // POSITIVO
                    'afiliacion_salud' => false,
                    'tipo_afiliacion_salud_id' => null,
                    'eps_id' => null,
                    'discapacidad' => false,
                    'tipo_discapacidad_id' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica_id' => 43, // INDIGENA
                    'nombre_etnia' => 'Wayúu',
                    'ocupacion_id' => 75, // PERSONA EN TRABAJO FORMAL
                    'barrio_id' => 108, // El Triunfo
                    'direccion' => 'Carrera 15 # 120-45',
                    'foto' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'Luis Fernando Gómez',
                    'email' => 'luis.gomez@example.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                'persona' => [
                    'tipo_documento_id' => 8, // CEDULA DE CIUDADANIA
                    'numero_documento' => '99887766',
                    'primer_nombre' => 'Luis Fernando',
                    'segundo_nombre' => 'José',
                    'primer_apellido' => 'Gómez',
                    'segundo_apellido' => 'Martínez',
                    'fecha_nacimiento' => '1983-09-25',
                    'sexo_id' => 13, // MASCULINO
                    'identidad_genero_id' => 15, // HOMBRE
                    'estado_civil_id' => 21, // CASADO
                    'telefono' => '6019988776',
                    'celular' => '3009988776',
                    'tipo_sangre_id' => 27, // O
                    'factor_rh_id' => 29, // NEGATIVO
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud_id' => 30, // CONTRIBUTIVO
                    'eps_id' => 35, // COOMEVA
                    'discapacidad' => false,
                    'tipo_discapacidad_id' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica_id' => 44, // AFROCOLOMBIANO
                    'nombre_etnia' => null,
                    'ocupacion_id' => 75, // PERSONA EN TRABAJO FORMAL
                    'barrio_id' => 109, // El Centro
                    'direccion' => 'Calle 26 # 8-15',
                    'foto' => null,
                ]
            ]
        ];

        // Crear usuarios y sus personas asociadas
        foreach ($usersData as $data) {
            $user = User::create($data['user']);

            $persona = new Persona($data['persona']);
            $persona->id = Str::uuid();
            $user->persona()->save($persona);
        }

        $this->command->info('Usuarios y personas creados exitosamente.');
    }
}
