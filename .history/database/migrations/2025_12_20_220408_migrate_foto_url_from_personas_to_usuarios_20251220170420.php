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
        // Copy foto_url data from personas table to usuarios table
        DB::statement('
            UPDATE usuarios
            SET foto_url = (
                SELECT p.foto_url
                FROM personas p
                WHERE p.id_persona = usuarios.persona_id
                AND p.foto_url IS NOT NULL
                AND p.foto_url != ""
            )
            WHERE persona_id IS NOT NULL
            AND (foto_url IS NULL OR foto_url = "")
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally clear foto_url from usuarios table
        DB::statement('UPDATE usuarios SET foto_url = NULL');
    }
};
