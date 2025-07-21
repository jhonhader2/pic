<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        $this->createParametrosTable();
        $this->createTemasTable();
        $this->createParametrosTemasTable();
        $this->createPersonasTable();
        $this->createEncuestasTable();
        $this->createEncuestaPersonasTable();
        $this->createEncuestaTemasTable();
        $this->createRespuestasTable();
        $this->createDetalleRespuestasTable();
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_respuestas');
        Schema::dropIfExists('respuestas');
        Schema::dropIfExists('encuesta_temas');
        Schema::dropIfExists('encuesta_personas');
        Schema::dropIfExists('encuestas');
        Schema::dropIfExists('personas');
        Schema::dropIfExists('parametros_temas');
        Schema::dropIfExists('temas');
        Schema::dropIfExists('parametros');
    }

    /**
     * Crea la tabla de parámetros.
     */
    private function createParametrosTable(): void
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->foreignId('user_create_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Crea la tabla de temas.
     */
    private function createTemasTable(): void
    {
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('descripcion')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('user_create_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Crea la tabla pivote parametros_temas.
     */
    private function createParametrosTemasTable(): void
    {
        Schema::create('parametros_temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parametro_id')->constrained('parametros')->onDelete('cascade');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->foreignId('user_create_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['parametro_id', 'tema_id']);
            $table->index('status');
        });
    }

    /**
     * Crea la tabla de personas.
     */
    private function createPersonasTable(): void
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
     * Crea la tabla de encuestas.
     */
    private function createEncuestasTable(): void
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activa')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['fecha_inicio', 'fecha_fin']);
            $table->index('activa');
        });
    }

    /**
     * Crea la tabla pivote encuesta_personas.
     */
    private function createEncuestaPersonasTable(): void
    {
        Schema::create('encuesta_personas', function (Blueprint $table) {
            $table->id();
            $table->uuid('encuesta_id');
            $table->uuid('persona_id');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->index(['encuesta_id', 'persona_id']);
        });
    }

    /**
     * Crea la tabla pivote encuesta_temas.
     */
    private function createEncuestaTemasTable(): void
    {
        Schema::create('encuesta_temas', function (Blueprint $table) {
            $table->id();
            $table->uuid('encuesta_id');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->index(['encuesta_id', 'tema_id']);
        });
    }

    /**
     * Crea la tabla de respuestas.
     */
    private function createRespuestasTable(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('encuesta_id');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_respuesta')->nullable();
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->index(['encuesta_id', 'usuario_id']);
        });
    }

    /**
     * Crea la tabla de detalle de respuestas.
     */
    private function createDetalleRespuestasTable(): void
    {
        Schema::create('detalle_respuestas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('respuesta_id');
            $table->foreignId('pregunta_id')->constrained('temas')->onDelete('cascade');
            $table->foreignId('parametro_id')->nullable()->constrained('parametros')->onDelete('set null');
            $table->integer('valor_numerico')->nullable();
            $table->date('fecha_respuesta')->nullable();
            $table->text('ruta_archivo')->nullable();
            $table->text('respuesta')->nullable();
            $table->timestamps();

            $table->foreign('respuesta_id')->references('id')->on('respuestas')->onDelete('cascade');
            $table->index(['respuesta_id', 'pregunta_id']);
            $table->index('parametro_id');
        });
    }
};
