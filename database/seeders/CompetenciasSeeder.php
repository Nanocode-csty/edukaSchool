<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InfAsignatura;
use App\Models\Competencia;
use Illuminate\Support\Facades\DB;

class CompetenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Definición del Currículo Nacional (CNEB)
        $curriculo = [
            'Matemática' => [
                'Resuelve problemas de cantidad',
                'Resuelve problemas de regularidad, equivalencia y cambio',
                'Resuelve problemas de forma, movimiento y localización',
                'Resuelve problemas de gestión de datos e incertidumbre'
            ],
            'Comunicación' => [
                'Se comunica oralmente en su lengua materna',
                'Lee diversos tipos de textos escritos en su lengua materna',
                'Escribe diversos tipos de textos en su lengua materna'
            ],
            'Inglés' => [
                'Se comunica oralmente en inglés como lengua extranjera',
                'Lee diversos tipos de textos en inglés como lengua extranjera',
                'Escribe diversos tipos de textos en inglés como lengua extranjera'
            ],
            'Ciencia y Tecnología' => [
                'Indaga mediante métodos científicos para construir sus conocimientos',
                'Explica el mundo físico basándose en conocimientos sobre los seres vivos, materia y energía, biodiversidad, Tierra y universo',
                'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno'
            ],
            'Personal Social' => [
                'Construye su identidad',
                'Convive y participa democráticamente en la búsqueda del bien común',
                'Construye interpretaciones históricas',
                'Gestiona responsablemente el espacio y el ambiente',
                'Gestiona responsablemente los recursos económicos'
            ],
            'Ciencias Sociales' => [ // Secundaria
                'Construye interpretaciones históricas',
                'Gestiona responsablemente el espacio y el ambiente',
                'Gestiona responsablemente los recursos económicos'
            ],
            'Desarrollo Personal' => [ // DPCC
                'Construye su identidad',
                'Convive y participa democráticamente en la búsqueda del bien común'
            ],
            'Arte' => [
                'Aprecia de manera crítica manifestaciones artístico-culturales',
                'Crea proyectos desde los lenguajes artísticos'
            ],
            'Educación Física' => [
                'Se desenvuelve de manera autónoma a través de su motricidad',
                'Asume una vida saludable',
                'Interactúa a través de sus habilidades sociomotrices'
            ],
            'Educación Religiosa' => [
                'Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente',
                'Asume la experiencia del encuentro personal y comunitario con Dios en su proyecto de vida'
            ],
            'Educación para el Trabajo' => [
                'Gestiona proyectos de emprendimiento económico o social'
            ],
            'Psicomotriz' => [
                'Se desenvuelve de manera autónoma a través de su motricidad'
            ]
        ];

        // 2. Mapeo de Nombres de Asignatura (BD) a Claves del Currículo
        $mapaAsignaturas = [
            'Matemática' => 'Matemática',
            'Matemáticas' => 'Matemática',
            'Comunicación' => 'Comunicación',
            'Lenguaje' => 'Comunicación',
            'Inglés' => 'Inglés',
            'Ciencia y Tecnología' => 'Ciencia y Tecnología',
            'Ciencias' => 'Ciencia y Tecnología',
            'Ciencias Naturales' => 'Ciencia y Tecnología',
            'Personal Social' => 'Personal Social',
            'Ciencias Sociales' => 'Ciencias Sociales',
            'Historia' => 'Ciencias Sociales',
            'Geografía' => 'Ciencias Sociales',
            'Desarrollo Personal, Ciudadanía y Cívica' => 'Desarrollo Personal',
            'Arte' => 'Arte',
            'Arte y Cultura' => 'Arte',
            'Música' => 'Arte',
            'Educación Física' => 'Educación Física',
            'Educación Religiosa' => 'Educación Religiosa',
            'Educación para el Trabajo' => 'Educación para el Trabajo',
            'Computación' => 'Educación para el Trabajo',
            'Psicomotriz' => 'Psicomotriz'
        ];

        // 3. Proceso de Inserción
        $asignaturas = InfAsignatura::all();
        $countTotal = 0;

        foreach ($asignaturas as $asignatura) {
            $nombreNormalizado = trim($asignatura->nombre);
            $claveEncontrada = null;

            // Intentar coincidencia exacta o parcial
            foreach ($mapaAsignaturas as $keyDB => $keyCurr) {
                if (stripos($nombreNormalizado, $keyDB) !== false) {
                    $claveEncontrada = $keyCurr;
                    break;
                }
            }

            if ($claveEncontrada && isset($curriculo[$claveEncontrada])) {
                // Verificar si ya tiene competencias asignadas para no duplicar
                if (Competencia::where('asignatura_id', $asignatura->asignatura_id)->exists()) {
                    continue;
                }

                $orden = 1;
                foreach ($curriculo[$claveEncontrada] as $desc) {
                    Competencia::create([
                        'asignatura_id' => $asignatura->asignatura_id,
                        'nombre' => 'C' . $orden,
                        'descripcion' => $desc,
                        'orden' => $orden
                    ]);
                    $orden++;
                    $countTotal++;
                }
            }
        }

        $this->command->info("Se han insertado {$countTotal} competencias para las asignaturas detectadas.");
    }
}
