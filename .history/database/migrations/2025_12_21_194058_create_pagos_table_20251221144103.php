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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('pago_id');
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('concepto_id');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->string('metodo_pago', 50)->nullable();
            $table->string('comprobante_url', 255)->nullable();
            $table->enum('estado', ['Pendiente', 'Pagado', 'Vencido', 'Cancelado'])->default('Pendiente');
            $table->string('codigo_transaccion', 100)->nullable();
            $table->unsignedBigInteger('usuario_registro')->nullable();
            $table->text('observaciones')->nullable();

            $table->foreign('matricula_id')->references('matricula_id')->on('matriculas');
            $table->foreign('concepto_id')->references('concepto_id')->on('conceptospago');
            $table->foreign('usuario_registro')->references('usuario_id')->on('usuarios');

            $table->index(['estado', 'fecha_vencimiento']);
            $table->index('matricula_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
