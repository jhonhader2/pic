<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario admin base
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@pic.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Pasar el id del admin a los seeders
        $this->call([
            ParametroSeeder::class,
            TemaSeeder::class,
            EncuestaTemaSeeder::class, // Nuevo seeder para preguntas de encuesta
            UserPersonaSeeder::class,
            EncuestaSeeder::class, // Nuevo seeder para encuesta de prueba
        ]);
    }
}
