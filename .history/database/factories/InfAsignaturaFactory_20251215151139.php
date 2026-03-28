<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfEstudiante>
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
            'dni' => $this->faker->numberBetween(10000000, 99999999),
            'nombres' => $this->faker->name(),
            'apellidos' => $this->faker->lastName(),
            'fecha_nacimiento' => $this->faker->date('Y-m-d'),
            'genero' => 'M',
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->numberBetween(900000000, 999999999),
            'email' => $this->faker->email(),
            'especialidad'=> $this->faker->words(1, true),
            'fecha_contratacion' => $this->faker->date('Y-m-d'),
            'estado' => 'Activo',
            'foto_url' => $this->faker->url(),
        ];
    }
}
