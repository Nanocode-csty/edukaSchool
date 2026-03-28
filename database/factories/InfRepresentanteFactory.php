<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfEstudiante>
 */
class InfRepresentanteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => $this->faker->unique()->numerify('########'),
            'nombres' => $this->faker->name(),
            'apellidos' => $this->faker->lastName(),
            'parentesco' => $this->faker->randomElement(['Padre', 'Madre', 'Tutor Legal', 'Otro']),
            'telefono' => $this->faker->unique()->numerify('9#######'),
            'telefono_alternativo' => $this->faker->optional()->numerify('9#######'),
            'email' => $this->faker->email(),
            'direccion' => $this->faker->address(),
            'ocupacion' => $this->faker->jobTitle(),
            'fecha_registro' => now(),
        ];
    }
}
