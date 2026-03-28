@extends('cplantilla.bprincipal')
@section('titulo','Mis Notificaciones')
@section('contenidoplantilla')
<x-breadcrumb :module="$modulo" :section="'notificaciones'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseNotificaciones" aria-expanded="true" aria-controls="collapseNotificaciones" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-bell m-1"></i>&nbsp;Mis Notificaciones
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-bell fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Aquí puedes ver todas tus notificaciones del sistema. Mantente al día con las actualizaciones importantes sobre justificaciones, asistencias y otros eventos relevantes.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros y tabla -->
                <div class="collapse show" id="collapseNotificaciones">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-primary {{ !request('leidas') ? 'active' : '' }}">
                                            Todas
                                        </a>
                                        <a href="{{ route('notificaciones.index', ['leidas' => 'false']) }}" class="btn btn-outline-warning {{ request('leidas') === 'false' ? 'active' : '' }}">
                                            No Leídas
                                        </a>
                                        <a href="{{ route('notificaciones.index', ['leidas' => 'true']) }}" class="btn btn-outline-secondary {{ request('leidas') === 'true' ? 'active' : '' }}">
                                            Leídas
                                        </a>
                                    </div>
                                    <button class="btn btn-success" onclick="marcarTodasComoLeidas()">
                                        <i class="fas fa-check-double"></i> Marcar Todas como Leídas
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if($notificaciones->count() > 0)
                            <div class="list-group">
                                @foreach($notificaciones as $notificacion)
                                <div class="list-group-item list-group-item-action {{ $notificacion->estaLeida() ? 'bg-light' : 'bg-white border-warning' }}" style="border-left: 4px solid {{ $notificacion->color }};">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $notificacion->icono }} fa-lg mr-3" style="color: {{ $notificacion->color }};"></i>
                                            <div>
                                                <h6 class="mb-1 {{ $notificacion->estaLeida() ? 'text-muted' : 'font-weight-bold' }}">
                                                    {{ $notificacion->titulo }}
                                                </h6>
                                                <p class="mb-1 {{ $notificacion->estaLeida() ? 'text-muted' : '' }}">
                                                    {{ $notificacion->mensaje }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $notificacion->tiempo_transcurrido }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @if(!$notificacion->estaLeida())
                                                <button class="btn btn-sm btn-outline-primary mr-2" onclick="marcarComoLeida({{ $notificacion->id }})">
                                                    <i class="fas fa-check"></i> Marcar como Leída
                                                </button>
                                            @endif
                                            @if($notificacion->url_accion)
                                                <a href="{{ $notificacion->url_accion }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-external-link-alt"></i> Ver
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Paginación -->
                            <div class="mt-3">
                                {{ $notificaciones->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash text-muted" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-muted">No hay notificaciones</h4>
                                <p class="text-muted">Cuando tengas nuevas notificaciones, aparecerán aquí.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseNotificaciones"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseNotificaciones');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });

                function marcarComoLeida(notificacionId) {
                    fetch(`{{ route('notificaciones.mark-read', ':id') }}`.replace(':id', notificacionId), {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al marcar como leída');
                    });
                }

                function marcarTodasComoLeidas() {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: '¿Estás seguro de marcar todas las notificaciones como leídas?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, marcar todas',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('notificaciones.mark-all-read') }}', {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: 'Todas las notificaciones han sido marcadas como leídas.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message || 'Error al marcar todas como leídas'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al marcar todas como leídas'
                                });
                            });
                        }
                    });
                }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
