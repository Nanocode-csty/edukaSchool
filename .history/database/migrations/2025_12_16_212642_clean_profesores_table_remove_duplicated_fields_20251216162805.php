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
        Schema::table('profesores', function (Blueprint $table) {
            // Remove duplicated fields that are now in personas table
            $table->dropColumn([
                'dni',
                'nombres',
                'apellidos',
                'fecha_nacimiento',
                'genero',
                'direccion',
                'telefono',
                'email'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesores', function (Blueprint $table) {
            // Restore the removed columns
            $table->char('dni', 8)->after('profesor_id');
            $table->string('nombres', 100)->after('dni');
            $table->string('apellidos', 100)->after('nombres');
            $table->date('fecha_nacimiento')->nullable()->after('apellidos');
            $table->enum('genero', ['M', 'F', 'Otro'])->nullable()->after('fecha_nacimiento');
            $table->string('direccion', 200)->nullable()->after('genero');
            $table->string('telefono', 20)->nullable()->after('direccion');
            $table->string('email', 120)->nullable()->after('telefono');
        });
    }
};
