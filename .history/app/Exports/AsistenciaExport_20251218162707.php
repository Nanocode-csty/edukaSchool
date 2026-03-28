<?php

namespace App\Exports;

use App\Models\AsistenciaAsignatura;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsistenciaExport implements FromCollection, WithHeadings, WithStyles
{
    protected $fechaInicio;

    protected $fechaFin;

    protected $cursoId;

    public function __construct($fechaInicio, $fechaFin, $cursoId = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->cursoId = $cursoId;
    }

    public function collection()
    {
        $query = AsistenciaAsignatura::with([
            'matricula.estudiante',
            'cursoAsignatura.asignatura',
            'cursoAsignatura.curso.grado',
            'cursoAsignatura.curso.seccion',
            'tipoAsistencia',
        ])
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);

        if ($this->cursoId) {
            $query->whereHas('cursoAsignatura', function ($q) {
                $q->where('curso_id', $this->cursoId);
            });
        }

        $asistencias = $query->orderBy('fecha')->get();

        return $asistencias->map(function ($asistencia) {
            return [
                'fecha' => $asistencia->fecha,
                'estudiante' => $asistencia->matricula->estudiante->nombres.' '.$asistencia->matricula->estudiante->apellidos,
                'documento' => $asistencia->matricula->estudiante->documento,
                'grado' => $asistencia->cursoAsignatura->curso->grado->nombre,
                'seccion' => $asistencia->cursoAsignatura->curso->seccion->nombre,
                'asignatura' => $asistencia->cursoAsignatura->asignatura->nombre,
                'tipo' => $asistencia->tipoAsistencia->nombre,
                'justificacion' => $asistencia->justificacion ?? '',
                'estado' => $asistencia->estado,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Estudiante',
            'Documento',
            'Grado',
            'Sección',
            'Asignatura',
            'Tipo Asistencia',
            'Justificación',
            'Estado',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
