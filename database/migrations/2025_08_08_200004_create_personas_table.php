<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de personas.
     * 
     * Responsabilidad: Gestionar la estructura de datos para la información
     * personal, médica, étnica y de ubicación de las personas del sistema.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Información de identificación
            $table->foreignId('tipo_documento_id')->constrained('parametros')->onDelete('restrict');
            $table->string('numero_documento')->unique();

            // Información personal
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->date('fecha_nacimiento');
            $table->foreignId('sexo_id')->constrained('parametros')->onDelete('restrict');
            $table->foreignId('identidad_genero_id')->nullable()->constrained('parametros')->onDelete('set null');
            $table->foreignId('estado_civil_id')->constrained('parametros')->onDelete('restrict');

            // Información de contacto
            $table->string('telefono')->nullable();
            $table->string('celular')->unique();

            // Información médica
            $table->foreignId('tipo_sangre_id')->constrained('parametros')->onDelete('restrict');
            $table->foreignId('factor_rh_id')->constrained('parametros')->onDelete('restrict');
            $table->boolean('afiliacion_salud')->default(true);
            $table->foreignId('tipo_afiliacion_salud_id')->nullable()->constrained('parametros')->onDelete('set null');
            $table->foreignId('eps_id')->nullable()->constrained('parametros')->onDelete('set null');

            // Información de discapacidad
            $table->boolean('discapacidad')->default(false);
            $table->foreignId('tipo_discapacidad_id')->nullable()->constrained('parametros')->onDelete('set null');
            $table->boolean('atencion_integral_discapacidad')->default(false);

            // Información étnica y ocupacional
            $table->foreignId('pertenencia_etnica_id')->constrained('parametros')->onDelete('restrict');
            $table->string('nombre_etnia')->nullable();
            $table->foreignId('ocupacion_id')->nullable()->constrained('parametros')->onDelete('set null');

            // Información de ubicación
            $table->foreignId('barrio_id')->constrained('parametros')->onDelete('restrict');
            $table->string('direccion');
            $table->string('foto')->nullable();

            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['tipo_documento_id', 'numero_documento']);
            $table->index('fecha_nacimiento');
            $table->index('sexo_id');
            $table->index('estado_civil_id');
            $table->index('afiliacion_salud');
            $table->index('discapacidad');
            $table->index('pertenencia_etnica_id');
            $table->index('barrio_id');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
