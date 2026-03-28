<?php

namespace App\Exports;

use App\Models\AsistenciaAsignatura;

class AsistenciaExport
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
     * @return array
     */
    public function getData()
    {
        $asistencias = $this->query->get();

        $data = [];

        // Add title and filters information at the top
        $data[] = ['REPORTE DE ASISTENCIA'];
        $data[] = ['Fecha de Generación: ' . $this->fechaGeneracion->format('d/m/Y H:i:s')];

        // Filters applied
        if (!empty($this->filtrosAplicados)) {
            $filterText = 'Filtros Aplicados: ' . implode(' | ', array_map(function($key, $value) {
                return ucfirst($key) . ': ' . $value;
            }, array_keys($this->filtrosAplicados), $this->filtrosAplicados));
            $data[] = [$filterText];
        }

        $data[] = ['']; // Empty row

        // Headers
        $data[] = [
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

        // Data rows
        foreach ($asistencias as $asistencia) {
            $data[] = [
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

        return $data;
    }
}
