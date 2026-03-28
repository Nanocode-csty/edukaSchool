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
        // Drop indexes and foreign key constraints using raw SQL
        $columns = ['estudiante_id', 'representante_id', 'profesor_id'];

        foreach ($columns as $column) {
            try {
                // Drop foreign key constraint first
                DB::statement("ALTER TABLE usuarios DROP FOREIGN KEY usuarios_{$column}_foreign");
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }

            try {
                // Drop index
                DB::statement("ALTER TABLE usuarios DROP INDEX {$column}");
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        }

        Schema::table('usuarios', function (Blueprint $table) {
            // Remove duplicated fields that are now handled by personas and roles relationships
            $columnsToDrop = [];
            if (Schema::hasColumn('usuarios', 'nombres')) {
                $columnsToDrop[] = 'nombres';
            }
            if (Schema::hasColumn('usuarios', 'apellidos')) {
                $columnsToDrop[] = 'apellidos';
            }
            if (Schema::hasColumn('usuarios', 'estudiante_id')) {
                $columnsToDrop[] = 'estudiante_id';
            }
            if (Schema::hasColumn('usuarios', 'representante_id')) {
                $columnsToDrop[] = 'representante_id';
            }
            if (Schema::hasColumn('usuarios', 'profesor_id')) {
                $columnsToDrop[] = 'profesor_id';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
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
