<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'dniCliente' => $this->faker->numberBetween(10000000, 99999999),  // 2 palabras como nombre
            'apellido_paterno' => $this->faker->words(1, true), // número entero
            'apellido_materno' => $this->faker->words(1, true), // número decimal con 2 decimales
            'nombres' => $this->faker->words(2, true), // número decimal con 2 decimales
            'estado' => 1, // 👈 Aquí
        ];

    }
}
