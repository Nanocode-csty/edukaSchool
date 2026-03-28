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
        Schema::create('periodos_matricula', function (Blueprint $table) {
            $table->id('periodo_id');
            $table->string('nombre', 100); // Ej: "Pre-inscripción 2026", "Inscripciones 2026"
            $table->string('codigo', 50)->unique(); // Ej: "PREINSCRIPCION_2026"
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('ano_lectivo_id')->unsigned(); // Relación con InfAnioLectivo
            $table->enum('tipo_periodo', [
                'PREINSCRIPCION',
                'INSCRIPCION',
                'MATRICULA',
                'ACADEMICO',
                'CIERRE'
            ]);
            $table->enum('estado', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
            $table->integer('orden')->default(0); // Para ordenar los períodos
            $table->json('configuracion')->nullable(); // Configuración adicional en JSON
            $table->timestamps();

            // Relación con años lectivos
            $table->foreign('ano_lectivo_id')->references('ano_lectivo_id')->on('anoslectivos');

            $table->index(['ano_lectivo_id', 'tipo_periodo']);
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos_matricula');
    }
};
