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
        Schema::create('persona_roles', function (Blueprint $table) {
            $table->id('persona_rol_id');
            $table->foreignId('persona_id')->constrained('personas', 'persona_id')->onDelete('cascade');
            $table->foreignId('rol_id')->constrained('roles', 'rol_id')->onDelete('cascade');
            $table->date('fecha_asignacion')->default(DB::raw('CURRENT_DATE'));
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();

            $table->unique(['persona_id', 'rol_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_roles');
    }
};
