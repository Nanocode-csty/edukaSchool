<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add persona_id to usuarios table if it doesn't exist
        if (!Schema::hasColumn('usuarios', 'persona_id')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->unsignedBigInteger('persona_id')->nullable()->after('google_token');
                $table->foreign('persona_id')->references('id_persona')->on('personas')->onDelete('set null');
            });
        }

        // Migrate data back from personas.usuario_id to usuarios.persona_id
        DB::statement('
            UPDATE usuarios u
            INNER JOIN personas p ON p.usuario_id = u.usuario_id
            SET u.persona_id = p.id_persona
            WHERE p.usuario_id IS NOT NULL
        ');

        // Remove usuario_id from personas table if it exists
        if (Schema::hasColumn('personas', 'usuario_id')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropForeign(['usuario_id']);
                $table->dropColumn('usuario_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add usuario_id back to personas table
        Schema::table('personas', function (Blueprint $table) {
            $table->unsignedInteger('usuario_id')->nullable()->unique()->after('estado');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuarios')->onDelete('set null');
        });

        // Migrate data back
        DB::statement('
            UPDATE personas p
            INNER JOIN usuarios u ON p.id_persona = u.persona_id
            SET p.usuario_id = u.usuario_id
            WHERE u.persona_id IS NOT NULL
        ');

        // Remove persona_id from usuarios table
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropColumn('persona_id');
        });
    }
};
