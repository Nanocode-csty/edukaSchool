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
        Schema::table('representantes', function (Blueprint $table) {
            // Remove duplicated fields that are now in personas table
            $table->dropColumn([
                'dni',
                'nombres',
                'apellidoPaterno',
                'apellidoMaterno',
                'telefono',
                'telefono_alternativo',
                'email',
                'direccion',
                'fecha_registro'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('representantes', function (Blueprint $table) {
            // Restore the removed columns
            $table->char('dni', 8)->after('representante_id');
            $table->string('nombres', 100)->after('dni');
            $table->string('apellidoPaterno', 100)->after('nombres');
            $table->string('apellidoMaterno', 100)->nullable()->after('apellidoPaterno');
            $table->string('telefono', 20)->nullable()->after('apellidoMaterno');
            $table->string('telefono_alternativo', 20)->nullable()->after('telefono');
            $table->string('email', 120)->nullable()->after('telefono_alternativo');
            $table->string('direccion', 200)->nullable()->after('email');
            $table->date('fecha_registro')->default(DB::raw('CURRENT_DATE'))->after('ocupacion');
        });
    }
};
