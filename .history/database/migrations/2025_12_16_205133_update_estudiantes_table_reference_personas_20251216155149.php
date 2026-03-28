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
            // Remove duplicated fields that are now in personas table
            $table->dropColumn([
                'dni', 'nombres', 'apellidos', 'fecha_nacimiento', 'genero',
                'direccion', 'telefono', 'email', 'fecha_registro', 'estado',
                'foto_url', 'observaciones'
            ]);

            // Add persona reference
            $table->foreignId('persona_id')->constrained('personas', 'persona_id')->onDelete('cascade');

            // Keep any student-specific fields here if needed
            // For now, just the persona reference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropColumn('persona_id');

            // Restore original columns
            $table->string('dni', 20)->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['M', 'F']);
            $table->text('direccion');
            $table->string('telefono', 20)->nullable();
            $table->string('email')->unique();
            $table->date('fecha_registro')->default(DB::raw('CURRENT_DATE'));
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('foto_url')->nullable();
            $table->text('observaciones')->nullable();
        });
    }
};
