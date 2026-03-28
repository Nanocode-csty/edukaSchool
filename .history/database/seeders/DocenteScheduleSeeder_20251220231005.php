<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InfDocente;
use App\Models\CursoAsignatura;
use App\Models\InfAula;
use App\Models\SesionClase;
use App\Models\InfCurso;
use App\Models\InfAsignatura;
use Carbon\Carbon;

class DocenteScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando horario de prueba para docente...');

        // Buscar el docente de prueba
        $docente = InfDocente::whereHas('persona', function($query) {
            $query->where('dni', '66666666');
        })->first();

        if (!$docente) {
            $this->command->error('No se encontró el docente de prueba. Ejecuta primero: php artisan db:seed --class=DocenteTestSeeder');
            return;
        }

        $this->command->info('Docente encontrado: ' . $docente->persona->nombres . ' ' . $docente->persona->apellidos);

        // Crear aulas si no existen
        $aulas = $this->crearAulas();

