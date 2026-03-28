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
            // Remove old polymorphic foreign keys
            $table->dropForeign(['profesor_id']);
            $table->dropForeign(['estudiante_id']);
            $table->dropForeign(['representante_id']);
            $table->dropColumn(['profesor_id', 'estudiante_id', 'representante_id']);

            // Add new persona relationship
            $table->foreignId('persona_id')->nullable()->constrained('personas', 'persona_id')->onDelete('cascade');
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
