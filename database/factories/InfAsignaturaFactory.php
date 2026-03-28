<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfAsignatura>
 */
class InfAsignaturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->bothify('???###'),
            'nombre' => $this->faker->randomElement([
                'Matemáticas',
                'Lenguaje',
                'Ciencias',
                'Historia',
                'Geografía',
                'Inglés',
                'Educación Física',
                'Arte',
                'Música',
                'Computación'
            ]),
            'descripcion' => $this->faker->sentence(),
            'horas_semanales' => $this->faker->numberBetween(2, 6),
        ];
    }
}
