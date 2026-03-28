<?php

namespace Database\Factories;

use App\Models\InfPeriodosEvaluacion;
use App\Models\InfAnioLectivo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfPeriodosEvaluacion>
 */
class InfPeriodosEvaluacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InfPeriodosEvaluacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Estados disponibles
        $estados = ['Planificado', 'En curso', 'Finalizado', 'Cerrado'];

        // Generar fechas lógicas
        $fechaInicio = $this->faker->dateTimeBetween('2024-01-01', '2024-10-01');
        $fechaFin = $this->faker->dateTimeBetween($fechaInicio, '+3 months');

        // Determinar si es final (solo el 25% de probabilidad)
        $esFinal = $this->faker->boolean(25);

        // Generar nombres únicos para evitar duplicados
        $nombreBase = $this->faker->randomElement([
            'Primer Bimestre',
            'Segundo Bimestre', 
            'Tercer Bimestre',
            'Cuarto Bimestre',
            'Evaluación Parcial',
            'Evaluación Trimestral',
            'Periodo de Nivelación',
            'Evaluación Extraordinaria'
        ]);

        // Agregar sufijo aleatorio para hacer único el nombre
        $nombre = $nombreBase . ' - ' . $this->faker->year() . ' ' . $this->faker->randomLetter();

        return [
            'ano_lectivo_id' => function() {
                // Intentar obtener un año lectivo existente, si no crear uno
                $anoLectivo = InfAnioLectivo::inRandomOrder()->first();
                return $anoLectivo ? $anoLectivo->ano_lectivo_id : 1;
            },
            'nombre' => $nombre,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $this->faker->randomElement($estados),
            'es_final' => $esFinal,
        ];
    }

    /**
     * Crear un periodo específico como Primer Bimestre
     */
    public function primerBimestre(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Primer Bimestre',
            'es_final' => false,
            'estado' => 'Finalizado',
        ]);
    }

    /**
     * Crear un periodo específico como Segundo Bimestre
     */
    public function segundoBimestre(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Segundo Bimestre',
            'es_final' => false,
            'estado' => 'En curso',
        ]);
    }

    /**
     * Crear un periodo específico como Tercer Bimestre
     */
    public function tercerBimestre(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Tercer Bimestre',
            'es_final' => false,
            'estado' => 'Planificado',
        ]);
    }

    /**
     * Crear un periodo específico como Cuarto Bimestre (Final)
     */
    public function cuartoBimestre(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Cuarto Bimestre',
            'es_final' => true,
            'estado' => 'Planificado',
        ]);
    }

    /**
     * Crear un periodo con estado activo
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => $this->faker->randomElement(['Planificado', 'En curso']),
        ]);
    }

    /**
     * Crear un periodo finalizado
     */
    public function finalizado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Finalizado',
        ]);
    }

    /**
     * Crear fechas específicas para un año lectivo
     */
    public function conFechas(Carbon $inicio, Carbon $fin): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_inicio' => $inicio,
            'fecha_fin' => $fin,
        ]);
    }
}