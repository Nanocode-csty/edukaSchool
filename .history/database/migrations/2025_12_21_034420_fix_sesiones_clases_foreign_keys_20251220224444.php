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
        Schema::table('sesiones_clases', function (Blueprint $table) {
            // Drop the old foreign key that references 'users' table
            $table->dropForeign(['usuario_registro']);

            // Add the new foreign key that references 'usuarios' table
            $table->foreign('usuario_registro')->references('usuario_id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesiones_clases', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['usuario_registro']);

            // Add back the old foreign key that references 'users' table
            $table->foreign('usuario_registro')->references('id')->on('users');
        });
    }
};
