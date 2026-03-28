@extends('cplantilla.bprincipal')

@section('contenidoplantilla')
    <div class="container">
        <h2>Detalle Asignación</h2>

        <table class="table">
            <tr>
                <th>ID</th>
                <td>{{ $item->curso_asignatura_id }}</td>
            </tr>
            <tr>
                <th>Curso</th>
                <td>{{ $item->curso->grado->nombre ?? 'Curso ' . $item->curso_id }}
                    {{ $item->curso->seccion->nombre ?? '' }}
                </td>
            </tr>
            <tr>
                <th>Asignatura</th>
                <td>{{ $item->asignatura->nombre ?? '' }}</td>
            </tr>
            <tr>
                <th>Profesor</th>
                <td>{{ $item->profesor->nombres ?? '' }} {{ $item->profesor->apellidos ?? '' }}</td>
            </tr>
            <tr>
                <th>Día</th>
                <td>{{ $item->dia_semana }}</td>
            </tr>
            <tr>
                <th>Horario</th>
                <td>{{ $item->hora_inicio }} - {{ $item->hora_fin }}</td>
            </tr>
            <tr>
                <th>Horas/Sem</th>
                <td>{{ $item->horas_semanales }}</td>
            </tr>
        </table>

        <a href="{{ route('cursoasignatura.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('cursoasignatura.edit', $item->curso_asignatura_id) }}" class="btn btn-warning">Editar</a>
    </div>
@endsection
