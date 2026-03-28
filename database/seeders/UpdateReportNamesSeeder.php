<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReporteGenerado;

class UpdateReportNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportes = ReporteGenerado::all();

        foreach ($reportes as $reporte) {
            // Handle both array and JSON string formats
            if (is_string($reporte->filtros_aplicados)) {
                $filtros = json_decode($reporte->filtros_aplicados, true) ?? [];
            } else {
                $filtros = $reporte->filtros_aplicados ?? [];
            }
            $tipoOriginal = $filtros['tipo_reporte'] ?? 'general';

            if ($reporte->formato === 'preview') {
                $nuevoNombre = 'Vista Previa ' . ucfirst($tipoOriginal) . ' (' . $reporte->fecha_inicio->format('Y-m-d') . ' - ' . $reporte->fecha_fin->format('Y-m-d') . ')';
            } elseif (strtolower($reporte->formato) === 'pdf') {
                $nuevoNombre = 'PDF Administrativo (' . $reporte->fecha_inicio->format('Y-m-d') . ' - ' . $reporte->fecha_fin->format('Y-m-d') . ')';
            } elseif (strtolower($reporte->formato) === 'excel' || strtolower($reporte->formato) === 'xlsx') {
                $nuevoNombre = 'Excel Administrativo (' . $reporte->fecha_inicio->format('Y-m-d') . ' - ' . $reporte->fecha_fin->format('Y-m-d') . ')';
            } else {
                $nuevoNombre = $reporte->tipo_reporte; // Mantener si no coincide
            }

            $reporte->update(['tipo_reporte' => $nuevoNombre]);
            echo 'Actualizado reporte ' . $reporte->id . ': ' . $reporte->tipo_reporte . PHP_EOL;
        }

        echo 'Actualización completada' . PHP_EOL;
    }
}