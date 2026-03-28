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
        // Check if the old table exists and has data
        if (Schema::hasTable('estudianterepresentante')) {
            $oldTableData = DB::table('estudianterepresentante')->get();

            if ($oldTableData->count() > 0) {
                foreach ($oldTableData as $record) {
                    // Map old column names to new ones
                    $esPrincipal = isset($record->es_principal) ? $record->es_principal : (isset($record->es_representante_principal) ? $record->es_representante_principal : false);
                    $viveConEstudiante = isset($record->viveConEstudiante) ? $record->viveConEstudiante : 'No';

                    DB::table('estudiante_representante')->updateOrInsert(
                        [
                            'estudiante_id' => $record->estudiante_id,
                            'representante_id' => $record->representante_id
                        ],
                        [
                            'es_principal' => $esPrincipal,
                            'viveConEstudiante' => $viveConEstudiante,
                            'created_at' => isset($record->created_at) ? $record->created_at : now(),
                            'updated_at' => isset($record->updated_at) ? $record->updated_at : now()
                        ]
                    );
                }
            }

            // Drop the old table
            Schema::dropIfExists('estudianterepresentante');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the old table if needed (for rollback)
        Schema::create('estudianterepresentante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('representante_id');
            $table->string('parentesco', 50);
            $table->boolean('es_principal')->default(false);
            $table->string('viveConEstudiante', 10)->default('No');
            $table->timestamps();

            $table->foreign('estudiante_id')->references('estudiante_id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('representante_id')->references('representante_id')->on('representantes')->onDelete('cascade');
            $table->unique(['estudiante_id', 'representante_id']);
        });

        // Migrate data back if the new table exists
        if (Schema::hasTable('estudiante_representante')) {
            $newTableData = DB::table('estudiante_representante')->get();

            foreach ($newTableData as $record) {
                DB::table('estudianterepresentante')->updateOrInsert(
                    [
                        'estudiante_id' => $record->estudiante_id,
                        'representante_id' => $record->representante_id
                    ],
                    [
                        'parentesco' => $record->parentesco ?? '',
                        'es_principal' => $record->es_principal,
                        'viveConEstudiante' => $record->viveConEstudiante,
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at
                    ]
                );
            }
        }
    }
};
