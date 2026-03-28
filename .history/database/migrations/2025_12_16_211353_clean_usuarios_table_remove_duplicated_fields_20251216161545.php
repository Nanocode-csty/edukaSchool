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
        // Drop foreign key constraints using raw SQL
        $foreignKeys = [
            'usuarios_estudiante_id_foreign',
            'usuarios_representante_id_foreign',
            'usuarios_profesor_id_foreign'
        ];

        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE usuarios DROP FOREIGN KEY {$fk}");
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }
        }

        Schema::table('usuarios', function (Blueprint $table) {
            // Remove duplicated fields that are now handled by personas and roles relationships
            $table->dropColumn([
                'nombres',
                'apellidos',
                'estudiante_id',
                'representante_id',
                'profesor_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Restore the removed columns
            $table->string('nombres', 100)->after('password_hash');
            $table->string('apellidos', 100)->after('nombres');
            $table->unsignedInteger('estudiante_id')->nullable()->after('cambio_password_requerido');
            $table->unsignedInteger('representante_id')->nullable()->after('estudiante_id');
            $table->unsignedInteger('profesor_id')->nullable()->after('representante_id');
        });
    }
};
