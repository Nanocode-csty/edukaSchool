<?php

namespace Database\Seeders;
use App\Models\InfPeriodosEvaluacion;
use Illuminate\Database\Seeder;

class PeriodosEvaluacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InfPeriodosEvaluacion::factory(10)->create();  // Para 10 registros
    }
}