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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id('matricula_id');
            $table->unsignedBigInteger('estudiante_id');
            $table->date('fecha_matricula');
            $table->enum('estado', ['Pre-inscrito', 'Matriculado', 'Anulado'])->default('Pre-inscrito');
            $table->string('numero_matricula', 20)->unique();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('usuario_registro')->nullable();
            $table->unsignedBigInteger('idGrado');
            $table->unsignedBigInteger('idSeccion');
            $table->year('anio_academico');

            $table->foreign('estudiante_id')->references('estudiante_id')->on('estudiantes');
            $table->foreign('usuario_registro')->references('usuario_id')->on('usuarios');
            $table->foreign('idGrado')->references('grado_id')->on('grados');
            $table->foreign('idSeccion')->references('seccion_id')->on('secciones');

            $table->index(['anio_academico', 'estado']);
            $table->index('numero_matricula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
