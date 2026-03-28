@extends('cplantilla.bprincipal')
@section('titulo', 'Resultado del Pago')
@section('contenidoplantilla')

<div class="container-fluid">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h3>
                        @if($status === 'success')
                            <i class="fas fa-check-circle text-success"></i> Pago Exitoso
                        @elseif($status === 'failure')
                            <i class="fas fa-times-circle text-danger"></i> Pago Fallido
                        @elseif($status === 'pending')
                            <i class="fas fa-clock text-warning"></i> Pago Pendiente
                        @endif
                    </h3>
                </div>
                <div class="card-body text-center">
                    @if($status === 'success')
                        <div class="alert alert-success">
                            <h4>¡Pago procesado exitosamente!</h4>
                            <p>Tu pago ha sido aprobado y registrado en el sistema.</p>
                        </div>
                    @elseif($status === 'failure')
                        <div class="alert alert-danger">
                            <h4>El pago no pudo ser procesado</h4>
                            <p>Hubo un problema al procesar tu pago. Por favor, intenta nuevamente.</p>
                        </div>
                    @elseif($status === 'pending')
                        <div class="alert alert-warning">
                            <h4>Pago en proceso</h4>
                            <p>Tu pago está siendo procesado. Recibirás una confirmación cuando se complete.</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('pagos.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver a Pagos
                        </a>
                        <a href="{{ route('rutarrr1') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-home"></i> Ir al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
