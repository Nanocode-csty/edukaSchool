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
        Schema::table('estudiantes', function (Blueprint $table) {
            // Add specific student fields
            $table->string('codigo_estudiante', 20)->unique()->nullable()->after('persona_id');
            $table->date('fecha_matricula')->nullable()->after('codigo_estudiante');
            $table->string('grado_actual', 10)->nullable()->after('fecha_matricula');
            $table->string('seccion_actual', 5)->nullable()->after('grado_actual');
            $table->enum('situacion_academica', ['Regular', 'Repetidor', 'Trasladado', 'Retirado'])->default('Regular')->after('seccion_actual');
            $table->text('observaciones_estudiante')->nullable()->after('situacion_academica');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn([
                'codigo_estudiante',
                'fecha_matricula',
                'grado_actual',
                'seccion_actual',
                'situacion_academica',
                'observaciones_estudiante'
            ]);
        });
    }
};
