<?php

namespace App\Exports;

use App\Models\AsistenciaAsignatura;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AsistenciaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $query;
    protected $filtrosAplicados;
    protected $fechaGeneracion;

    public function __construct($query, $filtrosAplicados = [])
    {
        $this->query = $query;
        $this->filtrosAplicados = $filtrosAplicados;
        $this->fechaGeneracion = now()->setTimezone('America/Lima');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            'Fecha',
            'Estudiante',
            'DNI',
            'Grado',
            'Sección',
            'Asignatura',
            'Docente',
            'Tipo de Asistencia',
            'Factor',
            'Justificado',
            'Observaciones'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($asistencia): array
    {
        return [
            $asistencia->fecha ? \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') : '',
            $asistencia->matricula && $asistencia->matricula->estudiante && $asistencia->matricula->estudiante->persona ?
                $asistencia->matricula->estudiante->persona->apellidos . ', ' . $asistencia->matricula->estudiante->persona->nombres : '',
            $asistencia->matricula && $asistencia->matricula->estudiante ? $asistencia->matricula->estudiante->dni : '',
            $asistencia->matricula && $asistencia->matricula->grado ? $asistencia->matricula->grado->nombre : '',
            $asistencia->matricula && $asistencia->matricula->seccion ? $asistencia->matricula->seccion->nombre : '',
            $asistencia->cursoAsignatura && $asistencia->cursoAsignatura->asignatura ? $asistencia->cursoAsignatura->asignatura->nombre : '',
            $asistencia->cursoAsignatura && $asistencia->cursoAsignatura->docente && $asistencia->cursoAsignatura->docente->persona ?
                $asistencia->cursoAsignatura->docente->persona->apellidos . ', ' . $asistencia->cursoAsignatura->docente->persona->nombres : '',
            $asistencia->tipoAsistencia ? $asistencia->tipoAsistencia->nombre : '',
            $asistencia->tipoAsistencia ? $asistencia->tipoAsistencia->factor_asistencia : 0,
            $asistencia->justificacion ? 'Sí' : 'No',
            $asistencia->observaciones ?? ''
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007BFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Get the last row
        $lastRow = $sheet->getHighestRow();

        // Style for data rows
        if ($lastRow > 1) {
            $sheet->getStyle('A2:K'.$lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Alternate row colors
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A'.$row.':K'.$row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8F9FA'],
                        ],
                    ]);
                }
            }

            // Center align the "Justificado" column
            $sheet->getStyle('J2:J'.$lastRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // Right align the "Factor" column
            $sheet->getStyle('I2:I'.$lastRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]);
        }

        // Add title and filters information at the top
        $sheet->insertNewRowBefore(1, 4);

        // Title
        $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIA');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Generation info
        $sheet->setCellValue('A2', 'Fecha de Generación: ' . $this->fechaGeneracion->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        // Filters applied
        if (!empty($this->filtrosAplicados)) {
            $filterText = 'Filtros Aplicados: ' . implode(' | ', array_map(function($key, $value) {
                return ucfirst($key) . ': ' . $value;
            }, array_keys($this->filtrosAplicados), $this->filtrosAplicados));

            $sheet->setCellValue('A3', $filterText);
            $sheet->mergeCells('A3:K3');
            $sheet->getStyle('A3')->applyFromArray([
                'font' => ['italic' => true, 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
        }

        // Empty row
        $sheet->setCellValue('A4', '');
        $sheet->mergeCells('A4:K4');

        return $sheet;
    }

    /**
     * Define the title of the worksheet
     */
    public function title(): string
    {
        return 'Asistencia';
    }
}
