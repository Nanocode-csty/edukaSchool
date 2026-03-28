<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfAnioLectivo>
 */
class InfAnioLectivoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->year(),
            'fecha_inicio' => Carbon::now()->startOfYear(),
            'fecha_fin' => Carbon::now()->endOfYear(),
            'estado' => $this->faker->randomElement(['Planificación', 'Activo', 'Finalizado']),
            'descripcion' => $this->faker->sentence(10),
        ];
    }
}