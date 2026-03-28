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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id'); // Recipient user
            $table->string('titulo', 255);
            $table->text('mensaje');
            $table->enum('tipo', [
                'justificacion_pendiente', // Admin: new justification to review
                'justificacion_aprobada', // User: justification approved
                'justificacion_rechazada', // User: justification rejected
                'asistencia_pendiente', // Teacher: attendance not taken
                'sistema', // System notifications
                'recordatorio' // Reminders
            ]);
            $table->json('datos')->nullable(); // Additional data (IDs, URLs, etc.)
            $table->timestamp('leido_en')->nullable(); // When user read it
            $table->string('url_accion')->nullable(); // URL to redirect when clicked
            $table->timestamps();

            // Indexes
            $table->index(['usuario_id', 'leido_en']);
            $table->index(['tipo', 'created_at']);
            $table->index('created_at');

            // Foreign key
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
