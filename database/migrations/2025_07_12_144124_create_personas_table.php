<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo_documento');
            $table->string('numero_documento')->unique();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->date('fecha_nacimiento');
            $table->boolean('sexo')->default(true);
            $table->string('identidad_genero')->nullable();
            $table->string('estado_civil');
            $table->string('telefono')->nullable();
            $table->string('celular')->unique();
            $table->string('tipo_sangre');
            $table->string('factor_rh');
            $table->boolean('afiliacion_salud')->default(true);
            $table->string('tipo_afiliacion_salud')->nullable();
            $table->string('eps')->nullable();
            $table->boolean('discapacidad')->default(false);
            $table->string('tipo_discapacidad')->nullable();
            $table->boolean('atencion_integral_discapacidad')->default(false);
            $table->string('pertenencia_etnica');
            $table->string('nombre_etnia')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('barrio');
            $table->string('direccion');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
