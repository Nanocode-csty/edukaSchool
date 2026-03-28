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
        Schema::create('conceptospago', function (Blueprint $table) {
            $table->id('concepto_id');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->decimal('monto', 10, 2);
            $table->boolean('recurrente')->default(false);
            $table->string('periodo', 50)->nullable();
            $table->unsignedBigInteger('ano_lectivo_id')->nullable();
            $table->unsignedBigInteger('nivel_id')->nullable();

            $table->foreign('ano_lectivo_id')->references('ano_lectivo_id')->on('anoslectivos');
            $table->foreign('nivel_id')->references('nivel_id')->on('niveles');

            $table->index(['nivel_id', 'ano_lectivo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceptospago');
    }
};
