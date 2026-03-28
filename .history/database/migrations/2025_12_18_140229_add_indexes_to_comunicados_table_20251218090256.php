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
        Schema::table('comunicados', function (Blueprint $table) {
            // Índices para optimizar consultas de comunicados
            $table->index(['fecha_inicio', 'fecha_fin'], 'idx_comunicados_fechas');
            $table->index('publico', 'idx_comunicados_publico');
            $table->index(['publico', 'fecha_inicio'], 'idx_comunicados_publico_fecha_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comunicados', function (Blueprint $table) {
            $table->dropIndex('idx_comunicados_fechas');
            $table->dropIndex('idx_comunicados_publico');
            $table->dropIndex('idx_comunicados_publico_fecha_inicio');
        });
    }
};
