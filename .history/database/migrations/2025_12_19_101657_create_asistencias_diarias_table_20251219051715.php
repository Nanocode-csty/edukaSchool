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
        Schema::create('asistenciasdiarias', function (Blueprint $table) {
            $table->id('asistencia_id');
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('curso_id')->nullable();
            $table->date('fecha');
            $table->unsignedBigInteger('tipo_asistencia_id');
            $table->time('hora_registro')->nullable();
            $table->boolean('justificado')->default(false);
            $table->text('documento_justificacion')->nullable();
            $table->string('estado', 20)->default('Activo');
            $table->unsignedBigInteger('usuario_registro')->nullable();
            $table->unsignedBigInteger('sesion_clase_id')->nullable();

            $table->foreign('matricula_id')->references('matricula_id')->on('matriculas');
            $table->foreign('curso_id')->references('curso_id')->on('inf_cursos');
            $table->foreign('tipo_asistencia_id')->references('tipo_asistencia_id')->on('tiposasistencia');
            $table->foreign('usuario_registro')->references('usuario_id')->on('users');
            $table->foreign('sesion_clase_id')->references('id')->on('sesion_clases');

            $table->index(['matricula_id', 'fecha']);
            $table->index('fecha');
            $table->index('tipo_asistencia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistenciasdiarias');
    }
};
