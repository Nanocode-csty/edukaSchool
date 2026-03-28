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
        Schema::create('notificaciones_periodos', function (Blueprint $table) {
            $table->id('notificacion_periodo_id');
            $table->string('titulo', 200);
            $table->text('mensaje');
            $table->enum('tipo_notificacion', [
                'PERIODO_INICIADO',
                'PERIODO_TERMINADO',
                'PERIODO_PROXIMO',
                'CAMBIO_ESTADO',
                'RECORDATORIO'
            ]);
            $table->integer('periodo_id')->unsigned();
            $table->integer('usuario_id')->unsigned()->nullable(); // Usuario específico o null para todos
            $table->json('datos_adicionales')->nullable(); // Información adicional en JSON
            $table->timestamp('fecha_programada')->nullable(); // Para notificaciones programadas
            $table->timestamp('fecha_enviada')->nullable(); // Cuando se envió
            $table->enum('estado', ['PENDIENTE', 'ENVIADA', 'CANCELADA'])->default('PENDIENTE');
            $table->timestamps();

            $table->foreign('periodo_id')->references('periodo_id')->on('periodos_matricula');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuarios');

            $table->index(['tipo_notificacion', 'estado']);
            $table->index(['fecha_programada']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_periodos');
    }
};
