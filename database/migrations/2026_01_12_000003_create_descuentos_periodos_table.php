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
        Schema::create('descuentos_periodos', function (Blueprint $table) {
            $table->id('descuento_id');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('periodo_id')->unsigned();
            $table->decimal('porcentaje_descuento', 5, 2); // 0.00 - 100.00
            $table->decimal('monto_fijo_descuento', 10, 2)->default(0); // Monto fijo opcional
            $table->enum('tipo_descuento', ['PORCENTAJE', 'FIJO', 'AMBOS']);
            $table->enum('aplicable_a', [
                'TODOS',
                'PREINSCRITOS',
                'MATRICULADOS',
                'REPITENTES',
                'NUEVOS'
            ])->default('TODOS');
            $table->date('fecha_inicio_vigencia');
            $table->date('fecha_fin_vigencia');
            $table->integer('limite_usos')->nullable(); // Número máximo de aplicaciones
            $table->integer('usos_actuales')->default(0);
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'EXPIRADO'])->default('ACTIVO');
            $table->json('condiciones')->nullable(); // Condiciones adicionales en JSON
            $table->integer('prioridad')->default(0); // Para ordenar descuentos aplicables
            $table->timestamps();

            $table->foreign('periodo_id')->references('periodo_id')->on('periodos_matricula');
            $table->index(['periodo_id', 'estado'], 'descuentos_periodo_estado_idx');
            $table->index(['fecha_inicio_vigencia', 'fecha_fin_vigencia'], 'descuentos_vigencia_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descuentos_periodos');
    }
};
