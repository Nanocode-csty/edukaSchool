@extends('cplantilla.bprincipal')
@section('titulo','Confirmar Eliminación')
@section('contenidoplantilla')
<div class="container">
    <h1>¿Desea eliminar este pago?</h1>
    <p>Código Transacción: {{ $pago->codigo_transaccion ?? 'Sin código' }}</p>
    <p>Matrícula: {{ $pago->matricula->numero_matricula ?? 'No asignada' }}</p>
    <p>Concepto: {{ $pago->concepto->nombre ?? 'No asignado' }}</p>
    <p>Monto: S/ {{ number_format($pago->monto, 2) }}</p>
    <p>Estado: {{ $pago->estado }}</p>
    <form method="POST" action="{{ route('pagos.destroy', $pago->pago_id) }}">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-danger"><i class="fas fa-check-square"></i> SÍ, Eliminar</button>
        <a href="{{ route('pagos.index') }}" class="btn btn-primary"><i class="fas fa-times-circle"></i> NO, Cancelar</a>
    </form>
</div>
@endsection
