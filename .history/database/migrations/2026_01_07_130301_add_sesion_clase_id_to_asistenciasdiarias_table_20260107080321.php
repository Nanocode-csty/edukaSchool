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
        Schema::table('asistenciasdiarias', function (Blueprint $table) {
            $table->unsignedBigInteger('sesion_clase_id')->nullable()->after('curso_id');
            $table->foreign('sesion_clase_id')->references('sesion_id')->on('sesiones_clases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistenciasdiarias', function (Blueprint $table) {
            $table->dropForeign(['sesion_clase_id']);
            $table->dropColumn('sesion_clase_id');
        });
    }
};
