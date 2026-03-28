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
        Schema::table('estudiante_representante', function (Blueprint $table) {
            // Rename es_representante_principal to es_principal if it exists
            if (Schema::hasColumn('estudiante_representante', 'es_representante_principal')) {
                $table->renameColumn('es_representante_principal', 'es_principal');
            } elseif (!Schema::hasColumn('estudiante_representante', 'es_principal')) {
                $table->boolean('es_principal')->default(false);
            }

            // Add viveConEstudiante column if it doesn't exist
            if (!Schema::hasColumn('estudiante_representante', 'viveConEstudiante')) {
                $table->string('viveConEstudiante', 10)->default('No');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiante_representante', function (Blueprint $table) {
            //
        });
    }
};
