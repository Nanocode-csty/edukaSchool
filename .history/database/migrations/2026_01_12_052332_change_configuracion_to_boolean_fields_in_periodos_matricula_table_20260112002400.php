<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periodos_matricula', function (Blueprint $table) {
            // Agregar campos booleanos para configuración
            $table->boolean('permite_preinscripcion')->default(false)->after('configuracion');
            $table->boolean('permite_inscripcion')->default(false)->after('permite_preinscripcion');
            $table->boolean('permite_matricula')->default(false)->after('permite_inscripcion');
            $table->boolean('clases_activas')->default(false)->after('permite_matricula');
        });

        // Migrar datos del JSON a campos booleanos
        DB::statement("
            UPDATE periodos_matricula
            SET
                permite_preinscripcion = JSON_EXTRACT(configuracion, '$.permite_preinscripcion') = true,
                permite_inscripcion = JSON_EXTRACT(configuracion, '$.permite_inscripcion') = true,
                permite_matricula = JSON_EXTRACT(configuracion, '$.permite_matricula') = true,
                clases_activas = JSON_EXTRACT(configuracion, '$.clases_activas') = true
            WHERE configuracion IS NOT NULL
        ");

        // Configurar valores por defecto según tipo de período
        DB::statement("
            UPDATE periodos_matricula
            SET permite_preinscripcion = true
            WHERE tipo_periodo = 'PREINSCRIPCION' AND permite_preinscripcion = false
        ");

        DB::statement("
            UPDATE periodos_matricula
            SET permite_inscripcion = true
            WHERE tipo_periodo = 'INSCRIPCION' AND permite_inscripcion = false
        ");

        DB::statement("
            UPDATE periodos_matricula
            SET permite_matricula = true
            WHERE tipo_periodo = 'MATRICULA' AND permite_matricula = false
        ");

        DB::statement("
            UPDATE periodos_matricula
            SET clases_activas = true
            WHERE tipo_periodo = 'ACADEMICO' AND clases_activas = false
        ");

        Schema::table('periodos_matricula', function (Blueprint $table) {
            // Eliminar columna JSON después de migrar datos
            $table->dropColumn('configuracion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periodos_matricula', function (Blueprint $table) {
            // Recrear columna JSON
            $table->json('configuracion')->nullable()->after('orden');
        });

        // Migrar datos de vuelta al JSON
        DB::statement("
            UPDATE periodos_matricula
            SET configuracion = JSON_OBJECT(
                'permite_preinscripcion', permite_preinscripcion,
                'permite_inscripcion', permite_inscripcion,
                'permite_matricula', permite_matricula,
                'clases_activas', clases_activas
            )
        ");

        Schema::table('periodos_matricula', function (Blueprint $table) {
            // Eliminar campos booleanos
            $table->dropColumn(['permite_preinscripcion', 'permite_inscripcion', 'permite_matricula', 'clases_activas']);
        });
    }
};
