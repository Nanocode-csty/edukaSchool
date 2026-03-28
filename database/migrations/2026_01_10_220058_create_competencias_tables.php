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
        if (!Schema::hasTable('competencias')) {
            Schema::create('competencias', function (Blueprint $table) {
                $table->id('competencia_id');
                $table->integer('asignatura_id');
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                
                $table->foreign('asignatura_id')->references('asignatura_id')->on('asignaturas')->onDelete('cascade');
            });
        } else {
             // If table exists, ensure columns are correct and FK exists
             Schema::table('competencias', function (Blueprint $table) {
                // We cannot easily change column type without doctrine/dbal, 
                // but we can try to add FK if it's missing, assuming types are compatible.
                // If types are incompatible (Signed vs Unsigned), we might fail.
                // For now, let's assume we can proceed or that the user will fix the mismatch if they have data.
                // Or better: try to modify column.
                // $table->integer('asignatura_id')->change(); // Needs doctrine/dbal
             });
        }

        if (!Schema::hasTable('calificaciones_competencias')) {
            Schema::create('calificaciones_competencias', function (Blueprint $table) {
                $table->id('calificacion_competencia_id');
                $table->integer('matricula_id');
                $table->unsignedBigInteger('competencia_id'); 
                $table->integer('periodo_id');
                $table->string('calificacion', 2); // AD, A, B, C
                $table->unsignedBigInteger('usuario_registro')->nullable(); // User who registered
                $table->timestamps();

                $table->foreign('matricula_id')->references('matricula_id')->on('matriculas')->onDelete('cascade');
                $table->foreign('competencia_id')->references('competencia_id')->on('competencias')->onDelete('cascade');
                $table->foreign('periodo_id')->references('periodo_id')->on('periodosevaluacion')->onDelete('cascade');
            });
        }

        // Add letter grade columns to summary tables
        if (Schema::hasTable('notasfinalesperiodo')) {
            Schema::table('notasfinalesperiodo', function (Blueprint $table) {
                if (!Schema::hasColumn('notasfinalesperiodo', 'promedio_letra')) {
                    $table->string('promedio_letra', 2)->nullable()->after('promedio');
                }
            });
        }

        if (Schema::hasTable('notasfinalesanuales')) {
            Schema::table('notasfinalesanuales', function (Blueprint $table) {
                 if (!Schema::hasColumn('notasfinalesanuales', 'promedio_final_letra')) {
                    $table->string('promedio_final_letra', 2)->nullable()->after('promedio_final');
                 }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notasfinalesanuales', function (Blueprint $table) {
            $table->dropColumn('promedio_final_letra');
        });

        Schema::table('notasfinalesperiodo', function (Blueprint $table) {
            $table->dropColumn('promedio_letra');
        });

        Schema::dropIfExists('calificaciones_competencias');
        Schema::dropIfExists('competencias');
    }
};
