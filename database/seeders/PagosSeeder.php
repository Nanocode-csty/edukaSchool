<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InfPago;
use App\Models\Matricula;
use App\Models\InfConceptoPago;
use Carbon\Carbon;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creando datos de prueba para pagos...');

        // Primero crear algunos conceptos de pago si no existen
        if (InfConceptoPago::count() == 0) {
            $this->command->info('Creando conceptos de pago...');
            InfConceptoPago::create([
                'nombre' => 'Matrícula Primaria',
                'descripcion' => 'Pago de matrícula para nivel primario',
                'monto' => 150.00,
                'recurrente' => false,
                'periodo' => 'Anual',
                'ano_lectivo_id' => 1,
                'nivel_id' => 1,
            ]);

            InfConceptoPago::create([
                'nombre' => 'Matrícula Secundaria',
                'descripcion' => 'Pago de matrícula para nivel secundario',
                'monto' => 200.00,
                'recurrente' => false,
                'periodo' => 'Anual',
                'ano_lectivo_id' => 1,
                'nivel_id' => 2,
            ]);

            InfConceptoPago::create([
                'nombre' => 'Mensualidad',
                'descripcion' => 'Pago mensual de pensión',
                'monto' => 80.00,
                'recurrente' => true,
                'periodo' => 'Mensual',
                'ano_lectivo_id' => 1,
                'nivel_id' => null,
            ]);
        }

        // Obtener matrículas existentes para crear pagos
        $matriculas = Matricula::where('estado', 'Matriculado')->take(10)->get();

        if ($matriculas->isEmpty()) {
            $this->command->warn('No hay matrículas matriculadas para crear pagos de prueba');
            return;
        }

        $conceptos = InfConceptoPago::all();

        foreach ($matriculas as $matricula) {
            // Crear pago de matrícula
            $conceptoMatricula = $conceptos->where('nombre', 'like', '%Matrícula%')->first();

            if ($conceptoMatricula) {
                InfPago::create([
                    'matricula_id' => $matricula->matricula_id,
                    'concepto_id' => $conceptoMatricula->concepto_id,
                    'monto' => $conceptoMatricula->monto,
                    'fecha_vencimiento' => Carbon::now()->addDays(rand(1, 30)),
                    'fecha_pago' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 15)) : null,
                    'metodo_pago' => rand(0, 1) ? 'Efectivo' : 'Transferencia',
                    'estado' => rand(0, 1) ? 'Pagado' : 'Pendiente',
                    'codigo_transaccion' => 'TXN-' . rand(100000, 999999),
                    'usuario_registro' => 1,
                    'observaciones' => 'Pago creado por seeder de pruebas',
                ]);
            }

            // Crear algunos pagos de mensualidades
            $conceptoMensualidad = $conceptos->where('nombre', 'Mensualidad')->first();

            if ($conceptoMensualidad) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    InfPago::create([
                        'matricula_id' => $matricula->matricula_id,
                        'concepto_id' => $conceptoMensualidad->concepto_id,
                        'monto' => $conceptoMensualidad->monto,
                        'fecha_vencimiento' => Carbon::now()->addMonths($i + 1),
                        'fecha_pago' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 10)) : null,
                        'metodo_pago' => rand(0, 1) ? 'Efectivo' : 'Transferencia',
                        'estado' => rand(0, 1) ? 'Pagado' : 'Pendiente',
                        'codigo_transaccion' => 'TXN-' . rand(100000, 999999),
                        'usuario_registro' => 1,
                        'observaciones' => 'Pago mensual creado por seeder de pruebas',
                    ]);
                }
            }
        }

        $this->command->info('Datos de pagos creados correctamente');
        $this->command->info('Total de pagos creados: ' . InfPago::count());
    }
}
