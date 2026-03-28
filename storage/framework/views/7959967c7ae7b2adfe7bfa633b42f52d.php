
<?php $__env->startSection('titulo','Mis Notificaciones'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>
<?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['module' => $modulo,'section' => 'notificaciones']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['module' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($modulo),'section' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('notificaciones')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
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
                                        <a href="<?php echo e(route('notificaciones.index')); ?>" class="btn btn-outline-primary <?php echo e(!request('leidas') ? 'active' : ''); ?>">
                                            Todas
                                        </a>
                                        <a href="<?php echo e(route('notificaciones.index', ['leidas' => 'false'])); ?>" class="btn btn-outline-warning <?php echo e(request('leidas') === 'false' ? 'active' : ''); ?>">
                                            No Leídas
                                        </a>
                                        <a href="<?php echo e(route('notificaciones.index', ['leidas' => 'true'])); ?>" class="btn btn-outline-secondary <?php echo e(request('leidas') === 'true' ? 'active' : ''); ?>">
                                            Leídas
                                        </a>
                                    </div>
                                    <button class="btn btn-success" onclick="marcarTodasComoLeidas()">
                                        <i class="fas fa-check-double"></i> Marcar Todas como Leídas
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php if($notificaciones->count() > 0): ?>
                            <div class="list-group">
                                <?php $__currentLoopData = $notificaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item list-group-item-action <?php echo e($notificacion->estaLeida() ? 'bg-light' : 'bg-white border-warning'); ?>" style="border-left: 4px solid <?php echo e($notificacion->color); ?>;">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="<?php echo e($notificacion->icono); ?> fa-lg mr-3" style="color: <?php echo e($notificacion->color); ?>;"></i>
                                            <div>
                                                <h6 class="mb-1 <?php echo e($notificacion->estaLeida() ? 'text-muted' : 'font-weight-bold'); ?>">
                                                    <?php echo e($notificacion->titulo); ?>

                                                </h6>
                                                <p class="mb-1 <?php echo e($notificacion->estaLeida() ? 'text-muted' : ''); ?>">
                                                    <?php echo e($notificacion->mensaje); ?>

                                                </p>
                                                <small class="text-muted">
                                                    <?php echo e($notificacion->tiempo_transcurrido); ?>

                                                </small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <?php if(!$notificacion->estaLeida()): ?>
                                                <button class="btn btn-sm btn-outline-primary mr-2" onclick="marcarComoLeida(<?php echo e($notificacion->id); ?>)">
                                                    <i class="fas fa-check"></i> Marcar como Leída
                                                </button>
                                            <?php endif; ?>
                                            <?php if($notificacion->url_accion): ?>
                                                <a href="<?php echo e($notificacion->url_accion); ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-external-link-alt"></i> Ver
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Paginación -->
                            <div class="mt-3">
                                <?php echo e($notificaciones->links()); ?>

                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash text-muted" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-muted">No hay notificaciones</h4>
                                <p class="text-muted">Cuando tengas nuevas notificaciones, aparecerán aquí.</p>
                            </div>
                        <?php endif; ?>
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
                    fetch(`<?php echo e(route('notificaciones.mark-read', ':id')); ?>`.replace(':id', notificacionId), {
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
                            // Update the notification item visually
                            const notificationItem = document.querySelector(`[data-notification-id="${notificacionId}"]`) ||
                                                   document.querySelector(`button[onclick*="marcarComoLeida(${notificacionId})"]`).closest('.list-group-item');

                            if (notificationItem) {
                                // Remove unread styling
                                notificationItem.classList.remove('bg-white', 'border-warning');
                                notificationItem.classList.add('bg-light');
                                notificationItem.style.borderLeftColor = '#6c757d'; // Gray color for read notifications

                                // Update text colors
                                const title = notificationItem.querySelector('.font-weight-bold');
                                const message = notificationItem.querySelector('.mb-1:not(.font-weight-bold)');

                                if (title) title.classList.remove('font-weight-bold');
                                if (message) message.classList.add('text-muted');

                                // Remove the "Marcar como Leída" button
                                const markAsReadBtn = notificationItem.querySelector('button[onclick*="marcarComoLeida"]');
                                if (markAsReadBtn) {
                                    markAsReadBtn.remove();
                                }
                            }

                            // Update notification count in navbar
                            updateNotificationCount();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Notificación marcada como leída.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al marcar como leída'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al marcar como leída'
                        });
                    });
                }

                // Function to update notification count in navbar
                function updateNotificationCount() {
                    fetch('<?php echo e(route('notificaciones.count-unread')); ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const badge = document.getElementById('notificationCount');
                                if (badge) {
                                    if (data.count > 0) {
                                        badge.textContent = data.count > 99 ? '99+' : data.count;
                                        badge.style.display = 'inline-block';
                                    } else {
                                        badge.style.display = 'none';
                                    }
                                }

                                // Also update mobile notification count
                                const mobileBadge = document.getElementById('mobile-notification-count');
                                if (mobileBadge) {
                                    if (data.count > 0) {
                                        mobileBadge.textContent = data.count > 99 ? '99+' : data.count;
                                        mobileBadge.style.display = 'inline-block';
                                    } else {
                                        mobileBadge.style.display = 'none';
                                    }
                                }
                            }
                        })
                        .catch(error => console.error('Error updating notification count:', error));
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
                            fetch('<?php echo e(route('notificaciones.mark-all-read')); ?>', {
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/notificaciones/index.blade.php ENDPATH**/ ?>