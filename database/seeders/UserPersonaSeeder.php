<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Persona;
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
                    'email' => 'admin@pic.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ],
                'persona' => [
                    'tipo_documento' => 'Cédula de Ciudadanía',
                    'numero_documento' => '12345678',
                    'primer_nombre' => 'Jeimy',
                    'segundo_nombre' => '',
                    'primer_apellido' => 'Acosta',
                    'segundo_apellido' => '',
                    'fecha_nacimiento' => '1985-03-15',
                    'sexo' => false, // Masculino
                    'identidad_genero' => 'NA',
                    'estado_civil' => 'Casado',
                    'telefono' => '6012345678',
                    'celular' => '3001234567',
                    'tipo_sangre' => 'O+',
                    'factor_rh' => 'Positivo',
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud' => 'Contributivo',
                    'eps' => 'Sura',
                    'discapacidad' => false,
                    'tipo_discapacidad' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica' => 'Mestizo',
                    'nombre_etnia' => null,
                    'ocupacion' => 'Ingeniero',
                    'barrio' => 'Centro',
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
                    'tipo_documento' => 'Cédula de Ciudadanía',
                    'numero_documento' => '87654321',
                    'primer_nombre' => 'María Elena',
                    'segundo_nombre' => 'Isabel',
                    'primer_apellido' => 'Rodríguez',
                    'segundo_apellido' => 'López',
                    'fecha_nacimiento' => '1990-07-22',
                    'sexo' => false, // Femenino
                    'identidad_genero' => 'Femenino',
                    'estado_civil' => 'Soltera',
                    'telefono' => '6018765432',
                    'celular' => '3008765432',
                    'tipo_sangre' => 'A+',
                    'factor_rh' => 'Positivo',
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud' => 'Contributivo',
                    'eps' => 'Nueva EPS',
                    'discapacidad' => false,
                    'tipo_discapacidad' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica' => 'Mestizo',
                    'nombre_etnia' => null,
                    'ocupacion' => 'Médica',
                    'barrio' => 'Chapinero',
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
                    'tipo_documento' => 'Cédula de Ciudadanía',
                    'numero_documento' => '11223344',
                    'primer_nombre' => 'Carlos Andrés',
                    'segundo_nombre' => null,
                    'primer_apellido' => 'Morales',
                    'segundo_apellido' => 'Hernández',
                    'fecha_nacimiento' => '1988-11-08',
                    'sexo' => true, // Masculino
                    'identidad_genero' => 'Masculino',
                    'estado_civil' => 'Divorciado',
                    'telefono' => '6011122334',
                    'celular' => '3001122334',
                    'tipo_sangre' => 'B+',
                    'factor_rh' => 'Positivo',
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud' => 'Subsidiado',
                    'eps' => 'Famisanar',
                    'discapacidad' => true,
                    'tipo_discapacidad' => 'Motora',
                    'atencion_integral_discapacidad' => true,
                    'pertenencia_etnica' => 'Mestizo',
                    'nombre_etnia' => null,
                    'ocupacion' => 'Docente',
                    'barrio' => 'Suba',
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
                    'tipo_documento' => 'Cédula de Ciudadanía',
                    'numero_documento' => '55667788',
                    'primer_nombre' => 'Ana Patricia',
                    'segundo_nombre' => 'Carmen',
                    'primer_apellido' => 'Silva',
                    'segundo_apellido' => 'Vargas',
                    'fecha_nacimiento' => '1992-04-12',
                    'sexo' => false, // Femenino
                    'identidad_genero' => 'Femenino',
                    'estado_civil' => 'Unión Libre',
                    'telefono' => '6015566778',
                    'celular' => '3005566778',
                    'tipo_sangre' => 'AB+',
                    'factor_rh' => 'Positivo',
                    'afiliacion_salud' => false,
                    'tipo_afiliacion_salud' => null,
                    'eps' => null,
                    'discapacidad' => false,
                    'tipo_discapacidad' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica' => 'Indígena',
                    'nombre_etnia' => 'Wayúu',
                    'ocupacion' => 'Arquitecta',
                    'barrio' => 'Usaquén',
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
                    'tipo_documento' => 'Cédula de Ciudadanía',
                    'numero_documento' => '99887766',
                    'primer_nombre' => 'Luis Fernando',
                    'segundo_nombre' => 'José',
                    'primer_apellido' => 'Gómez',
                    'segundo_apellido' => 'Martínez',
                    'fecha_nacimiento' => '1983-09-25',
                    'sexo' => true, // Masculino
                    'identidad_genero' => 'Masculino',
                    'estado_civil' => 'Casado',
                    'telefono' => '6019988776',
                    'celular' => '3009988776',
                    'tipo_sangre' => 'O-',
                    'factor_rh' => 'Negativo',
                    'afiliacion_salud' => true,
                    'tipo_afiliacion_salud' => 'Contributivo',
                    'eps' => 'Colsanitas',
                    'discapacidad' => false,
                    'tipo_discapacidad' => null,
                    'atencion_integral_discapacidad' => false,
                    'pertenencia_etnica' => 'Afrocolombiano',
                    'nombre_etnia' => null,
                    'ocupacion' => 'Abogado',
                    'barrio' => 'Teusaquillo',
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