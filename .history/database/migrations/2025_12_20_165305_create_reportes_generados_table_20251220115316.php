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
        // Crear tabla para almacenar el historial de reportes de asistencia generados
        Schema::create('reportes_generados', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_reporte'); // 'general', 'por_curso', 'por_estudiante', 'por_docente', 'comparativo'
            $table->string('formato'); // 'pdf', 'excel'
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->json('filtros_aplicados')->nullable(); // Almacenar filtros aplicados en JSON
            $table->string('archivo_path')->nullable(); // Ruta del archivo generado
            $table->string('archivo_nombre'); // Nombre del archivo
            $table->integer('registros_totales')->default(0); // Número de registros en el reporte
            $table->decimal('tamano_archivo_kb', 10, 2)->nullable(); // Tamaño del archivo en KB
            $table->unsignedBigInteger('usuario_id'); // Usuario que generó el reporte
            $table->timestamp('fecha_generacion');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['usuario_id', 'fecha_generacion']);
            $table->index('tipo_reporte');
            $table->index('fecha_generacion');

            // Llaves foráneas
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_generados');
    }
};
