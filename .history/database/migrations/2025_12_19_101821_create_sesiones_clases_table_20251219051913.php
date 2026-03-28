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
        Schema::create('sesiones_clases', function (Blueprint $table) {
            $table->id('sesion_id');
            $table->unsignedBigInteger('curso_asignatura_id');
            $table->unsignedBigInteger('docente_id');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('estado', 20)->default('Programada');
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('aula_id')->nullable();
            $table->string('tipo_sesion', 50)->default('Regular');
            $table->unsignedBigInteger('usuario_registro')->nullable();
            $table->boolean('tiene_asistencia_hoy')->default(false);

            $table->foreign('curso_asignatura_id')->references('curso_asignatura_id')->on('cursoasignaturas');
            $table->foreign('docente_id')->references('profesor_id')->on('profesores');
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->foreign('usuario_registro')->references('usuario_id')->on('users');

            $table->index(['fecha', 'hora_inicio']);
            $table->index('docente_id');
            $table->index('curso_asignatura_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones_clases');
    }
};
