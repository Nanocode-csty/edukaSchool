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
        Schema::table('representantes', function (Blueprint $table) {
            // Add persona reference if it doesn't exist
            if (!Schema::hasColumn('representantes', 'persona_id')) {
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
        Schema::table('representantes', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropColumn('persona_id');

            // Restore original columns
            $table->string('dni', 20)->unique();
            $table->string('nombres');
            $table->string('apellidoPaterno');
            $table->string('apellidoMaterno')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('telefono_alternativo', 20)->nullable();
            $table->string('email')->unique();
            $table->text('direccion');
            $table->string('ocupacion')->nullable();
            $table->date('fecha_registro')->default(DB::raw('CURRENT_DATE'));
        });
    }
};
