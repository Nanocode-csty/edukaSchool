<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfEstudiante>
 */
class InfDocenteFactory extends Factory
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
            'fecha_nacimiento' => $this->faker->date('Y-m-d'),
            'genero' => $this->faker->randomElement(['M', 'F']),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->unique()->numerify('9########'),
            'email' => $this->faker->email(),
            'especialidad'=> $this->faker->jobTitle(),
            'fecha_contratacion' => $this->faker->date('Y-m-d'),
            'estado' => 'Activo',
            'foto_url' => 'storage/fotos/imgDocente.png',
        ];
    }
}
