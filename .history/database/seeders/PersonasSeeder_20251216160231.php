<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personas = [
            // Estudiantes
            [
                'dni' => '12345678',
                'nombres' => 'Juan Carlos',
                'apellidos' => 'García López',
                'fecha_nacimiento' => '2010-05-15',
                'genero' => 'M',
                'direccion' => 'Av. Larco 123, Miraflores, Lima',
                'telefono' => '987654321',
                'email' => 'juan.garcia@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            [
                'dni' => '87654321',
                'nombres' => 'María José',
                'apellidos' => 'Rodríguez Sánchez',
                'fecha_nacimiento' => '2011-03-22',
                'genero' => 'F',
                'direccion' => 'Jr. de la Unión 456, Cercado de Lima',
                'telefono' => '987654322',
                'email' => 'maria.rodriguez@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            [
                'dni' => '11223344',
                'nombres' => 'Pedro Antonio',
                'apellidos' => 'Martínez Gómez',
                'fecha_nacimiento' => '2009-11-08',
                'genero' => 'M',
                'direccion' => 'Av. Arequipa 789, San Isidro, Lima',
                'telefono' => '987654323',
                'email' => 'pedro.martinez@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            // Docentes
            [
                'dni' => '44556677',
                'nombres' => 'Ana María',
                'apellidos' => 'Fernández Torres',
                'fecha_nacimiento' => '1985-07-12',
                'genero' => 'F',
                'direccion' => 'Calle Los Olivos 321, Surco, Lima',
                'telefono' => '987654324',
                'email' => 'ana.fernandez@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            [
                'dni' => '55667788',
                'nombres' => 'Carlos Alberto',
                'apellidos' => 'Díaz Ruiz',
                'fecha_nacimiento' => '1982-09-30',
                'genero' => 'M',
                'direccion' => 'Av. Javier Prado 654, La Molina, Lima',
                'telefono' => '987654325',
                'email' => 'carlos.diaz@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            // Representantes
            [
                'dni' => '66778899',
                'nombres' => 'Rosa Elena',
                'apellidos' => 'Vargas Pérez',
                'fecha_nacimiento' => '1978-12-05',
                'genero' => 'F',
                'direccion' => 'Jr. Huancavelica 987, Barranco, Lima',
                'telefono' => '987654326',
                'email' => 'rosa.vargas@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            [
                'dni' => '77889900',
                'nombres' => 'Luis Miguel',
                'apellidos' => 'Castro Jiménez',
                'fecha_nacimiento' => '1975-04-18',
                'genero' => 'M',
                'direccion' => 'Av. Brasil 147, Jesús María, Lima',
                'telefono' => '987654327',
                'email' => 'luis.castro@colegio.edu.pe',
                'estado' => 'Activo'
            ],
            // Administrador
            [
                'dni' => '88990011',
                'nombres' => 'Patricia Isabel',
                'apellidos' => 'Morales Herrera',
                'fecha_nacimiento' => '1980-01-25',
                'genero' => 'F',
                'direccion' => 'Calle Schell 258, Miraflores, Lima',
                'telefono' => '987654328',
                'email' => 'patricia.morales@colegio.edu.pe',
                'estado' => 'Activo'
            ]
        ];

        foreach ($personas as $persona) {
            \App\Models\Persona::create($persona);
        }
    }
}
