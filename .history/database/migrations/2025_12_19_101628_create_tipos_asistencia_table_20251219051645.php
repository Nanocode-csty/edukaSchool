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
        Schema::create('tiposasistencia', function (Blueprint $table) {
            $table->id('tipo_asistencia_id');
            $table->string('codigo', 5)->unique();
            $table->string('nombre', 50);
            $table->text('descripcion')->nullable();
            $table->boolean('computa_falta')->default(false);
            $table->decimal('factor_asistencia', 3, 2)->default(1.00);
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiposasistencia');
    }
};
