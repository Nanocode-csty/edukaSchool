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
        Schema::create('asistenciasasignatura', function (Blueprint $table) {
            $table->id('asistencia_asignatura_id');
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('curso_asignatura_id');
            $table->date('fecha');
            $table->unsignedBigInteger('tipo_asistencia_id');
            $table->time('hora_registro')->nullable();
            $table->text('justificacion')->nullable();
            $table->string('documento_justificacion', 200)->nullable();
            $table->unsignedBigInteger('usuario_registro')->nullable();
            $table->enum('estado', ['Registrada', 'Justificada', 'Verificada'])->default('Registrada');

            $table->foreign('matricula_id')->references('matricula_id')->on('matriculas');
