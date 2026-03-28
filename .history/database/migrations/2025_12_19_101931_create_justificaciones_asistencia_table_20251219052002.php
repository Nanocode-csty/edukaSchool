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
        Schema::create('justificaciones_asistencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamp('fecha_solicitud');
            $table->date('fecha_falta');
            $table->text('motivo');
            $table->string('documento_adjunto')->nullable();
            $table->string('estado', 20)->default('Pendiente');
            $table->text('observaciones_admin')->nullable();
            $table->timestamp('fecha_revision')->nullable();
            $table->unsignedBigInteger('revisado_por')->nullable();
            $table->timestamps();

            $table->foreign('matricula_id')->references('matricula_id')->on('matriculas');
            $table->foreign('usuario_id')->references('usuario_id')->on('users');
            $table->foreign('revisado_por')->references('usuario_id')->on('users');

            $table->index(['matricula_id', 'fecha_falta']);
            $table->index('estado');
            $table->index('fecha_solicitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justificaciones_asistencia');
    }
};
