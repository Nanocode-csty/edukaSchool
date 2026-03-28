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
        Schema::table('usuarios', function (Blueprint $table) {
            // Add new persona relationship if it doesn't exist
            if (!Schema::hasColumn('usuarios', 'persona_id')) {
                $table->unsignedBigInteger('persona_id')->nullable();
                // Add foreign key constraint separately if personas table exists
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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropColumn('persona_id');

            // Restore old columns
            $table->unsignedBigInteger('profesor_id')->nullable();
            $table->unsignedBigInteger('estudiante_id')->nullable();
            $table->unsignedBigInteger('representante_id')->nullable();

            // Add back foreign keys if needed
            $table->foreign('profesor_id')->references('profesor_id')->on('profesores');
            $table->foreign('estudiante_id')->references('estudiante_id')->on('estudiantes');
            $table->foreign('representante_id')->references('representante_id')->on('representantes');
        });
    }
};
