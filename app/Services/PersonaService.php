<?php

namespace App\Services;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersonaService
{
    /**
     * Crear una nueva persona asociada a un usuario
     */
    public function createPersonaFromUser(User $user, array $data): Persona
    {
        return $user->persona()->create($this->preparePersonaData($data));
    }

    /**
     * Crear solo una persona (sin usuario)
     */
    public function createPersonaOnly(array $data, ?User $createdBy = null): Persona
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // Crear usuario vacío primero
            $user = User::create([
                'name' => $this->buildNombreCompleto($data),
                'email' => $data['numero_documento'] . '@temp.local', // Email temporal único
                'password' => bcrypt('temp_password_' . uniqid()),
                'email_verified_at' => null
            ]);

            $personaData = $this->preparePersonaData($data);
            $personaData['user_id'] = $user->id;

            return Persona::create($personaData);
        });
    }

    /**
     * Crear usuario y persona en una transacción (solo para admins)
     */
    public function createUserWithPersona(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->createUser($data);
            $this->createPersonaFromUser($user, $data);
            return $user;
        });
    }

    /**
     * Crear persona según el rol del usuario autenticado
     */
    public function createPersonaByRole(array $data): Persona|User
    {
        $currentUser = Auth::user();

        return $this->isAdmin($currentUser)
            ? $this->createUserWithPersona($data)
            : $this->createPersonaOnly($data, $currentUser);
    }

    /**
     * Preparar datos de persona para crear/actualizar
     */
    private function preparePersonaData(array $data): array
    {
        return [
            'tipo_documento_id' => $data['tipo_documento'],
            'sexo_id' => $data['sexo'],
            'estado_civil_id' => $data['estado_civil'],
            'tipo_sangre_id' => $data['tipo_sangre'],
            'factor_rh_id' => $data['factor_rh'],
            'pertenencia_etnica_id' => $data['pertenencia_etnica'],
            'ocupacion_id' => $data['ocupacion'],
            'barrio_id' => $data['barrio'],
            'numero_documento' => $data['numero_documento'],
            'primer_nombre' => $data['primer_nombre'],
            'segundo_nombre' => $data['segundo_nombre'] ?? null,
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'] ?? null,
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'identidad_genero_id' => $data['identidad_genero'],
            'telefono' => $data['telefono'] ?? null,
            'celular' => $data['celular'],
            'afiliacion_salud' => $this->parseBoolean($data['afiliacion_salud'] ?? null),
            'tipo_afiliacion_salud_id' => $this->getConditionalValue($data['afiliacion_salud'] ?? null, $data['tipo_afiliacion_salud'] ?? null),
            'eps_id' => $this->getConditionalValue($data['afiliacion_salud'] ?? null, $data['eps'] ?? null),
            'discapacidad' => $this->parseBoolean($data['discapacidad'] ?? null),
            'tipo_discapacidad_id' => $this->getConditionalValue($data['discapacidad'] ?? null, $data['tipo_discapacidad'] ?? null),
            'atencion_integral_discapacidad' => $this->getConditionalBoolean($data['discapacidad'] ?? null, $data['atencion_integral_discapacidad'] ?? null),
            'nombre_etnia' => $this->getConditionalEtniaName($data['pertenencia_etnica'] ?? null, $data['nombre_etnia'] ?? null),
            'direccion' => $data['direccion'],
        ];
    }

    /**
     * Parsear valor booleano desde string
     */
    private function parseBoolean($value): bool
    {
        return ($value ?? '0') == '1';
    }

    /**
     * Obtener valor condicional basado en condición booleana
     */
    private function getConditionalValue($condition, $value): ?int
    {
        return $this->parseBoolean($condition) ? ($value ?? null) : null;
    }

    /**
     * Obtener valor booleano condicional
     */
    private function getConditionalBoolean($condition, $value): bool
    {
        return $this->parseBoolean($condition) && $this->parseBoolean($value);
    }

    /**
     * Obtener nombre de etnia condicional
     */
    private function getConditionalEtniaName($pertenenciaEtnica, $nombreEtnia): ?string
    {
        // Si es "NO DEFINE" (ID 5) o vacío, no guardar nombre de etnia
        if (!$pertenenciaEtnica || $pertenenciaEtnica == '5') {
            return null;
        }

        return $nombreEtnia;
    }

    /**
     * Verificar si el usuario es admin
     */
    private function isAdmin(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->is_admin ?? false;
    }

    /**
     * Construir el nombre completo de una persona
     */
    public function buildNombreCompleto(array $data): string
    {
        $nombres = array_filter([
            $data['primer_nombre'],
            $data['segundo_nombre'] ?? null,
            $data['primer_apellido'],
            $data['segundo_apellido'] ?? null
        ]);

        return trim(implode(' ', $nombres));
    }

    /**
     * Crear un nuevo usuario
     */
    private function createUser(array $data): User
    {
        return User::create([
            'name' => $this->buildNombreCompleto($data),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
