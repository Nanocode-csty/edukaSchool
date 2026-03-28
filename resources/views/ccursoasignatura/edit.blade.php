@extends('cplantilla.bprincipal')

@section('contenidoplantilla')
    <div class="container">
        <h2>Editar Asignación</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('cursoasignatura.update', $item->curso_asignatura_id) }}" method="POST">
            @method('PUT')
            @include('ccursoasignatura._form')
        </form>
    </div>
@endsection
