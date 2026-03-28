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
            // Add persona reference if it doesn't exist
            if (!Schema::hasColumn('estudiantes', 'persona_id')) {
                $table->unsignedBigInteger('persona_id')->nullable();
                // Add foreign key if personas table exists
                if (Schema::hasTable('personas')) {
                    $table->foreign('persona_id')->references('persona_id')->on('personas')->onDelete('cascade');
                }
            }
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
