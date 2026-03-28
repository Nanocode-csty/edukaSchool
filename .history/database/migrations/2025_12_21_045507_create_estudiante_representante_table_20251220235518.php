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
        Schema::create('estudiante_representante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('representante_id');
            $table->string('parentesco', 50);
            $table->boolean('es_representante_principal')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('representante_id')->references('representante_id')->on('representantes')->onDelete('cascade');

            // Unique constraint to prevent duplicate relationships
            $table->unique(['estudiante_id', 'representante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiante_representante');
    }
};
