<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfSeccion>
 */
class InfSeccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => chr(65 + $this->faker->numberBetween(0, 25)), // A-Z
            'capacidad_maxima' => $this->faker->numberBetween(15, 60),
            'descripcion' => $this->faker->sentence(),
            'estado' => 'Activo',
        ];
    }
}
