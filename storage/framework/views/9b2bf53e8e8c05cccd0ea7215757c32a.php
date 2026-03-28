
<?php $__env->startSection('titulo', 'Mi Perfil'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

<style>
    .profile-header {
        background: linear-gradient(135deg, #0F3E61, #2378ba);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.8);
        object-fit: cover;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }

    .info-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .info-card:hover {
        transform: translateY(-2px);
    }

    .info-label {
        font-weight: 600;
        color: #003f77;
        font-size: 0.9rem;
    }

    .info-value {
        color: #495057;
        font-size: 0.95rem;
    }

    .role-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
        margin: 5px;
    }

    .section-title {
        color: #003f77;
        font-weight: 700;
        margin-bottom: 20px;
        border-left: 5px solid #0d6efd;
        padding-left: 15px;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .btn-edit {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.4);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                            <img src="<?php echo e($user->foto_url ? asset('storage/' . $user->foto_url) . '?v=' . time() : asset('adminlte/assets/img/profile.jpg')); ?>"
                                 alt="Foto de perfil"
                                 class="profile-avatar mb-3">
                        <h5 class="mb-0"><?php echo e($persona->nombre_completo); ?></h5>
                        <div class="mt-2">
                            <?php $__currentLoopData = $user->getRoles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="role-badge"><?php echo e($role->nombre); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-3">Información Personal</h4>
                                <div class="mb-2">
                                    <strong>DNI:</strong> <?php echo e($persona->dni ?? 'No registrado'); ?>

                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong> <?php echo e($user->email); ?>

                                </div>
                                <div class="mb-2">
                                    <strong>Teléfono:</strong> <?php echo e($persona->telefono ?? 'No registrado'); ?>

                                </div>
                                <div class="mb-2">
                                    <strong>Fecha de Nacimiento:</strong>
                                    <?php echo e($persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('d/m/Y') : 'No registrada'); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-3">Información del Sistema</h4>
                                <div class="mb-2">
                                    <strong>Usuario:</strong> <?php echo e($user->username); ?>

                                </div>
                                <div class="mb-2">
                                    <strong>Última Sesión:</strong>
                                    <?php echo e($user->ultima_sesion ? $user->ultima_sesion->format('d/m/Y H:i') : 'Nunca'); ?>

                                </div>
                                <div class="mb-2">
                                    <strong>Estado:</strong>
                                    <span class="badge <?php echo e($user->estado == 'Activo' ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($user->estado); ?>

                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Miembro desde:</strong>
                                    <?php echo e($persona->created_at ? $persona->created_at->format('d/m/Y') : 'N/A'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-light btn-edit">
                                <i class="fas fa-edit me-2"></i>Editar Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Información Básica -->
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información Básica</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Nombres</div>
                                        <div class="info-value"><?php echo e($persona->nombres); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Apellidos</div>
                                        <div class="info-value"><?php echo e($persona->apellidos); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Género</div>
                                        <div class="info-value">
                                            <?php if($persona->genero == 'M'): ?>
                                                Masculino
                                            <?php elseif($persona->genero == 'F'): ?>
                                                Femenino
                                            <?php else: ?>
                                                No especificado
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Dirección</div>
                                        <div class="info-value"><?php echo e($persona->direccion ?? 'No registrada'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Información de Contacto</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?php echo e($user->email); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="info-label">Teléfono</div>
                                <div class="info-value"><?php echo e($persona->telefono ?? 'No registrado'); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="info-label">Dirección</div>
                                <div class="info-value"><?php echo e($persona->direccion ?? 'No registrada'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Específica por Rol -->
            <?php if(isset($roleSpecificData['docente']) && $roleSpecificData['docente']): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Información Docente</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="info-label">Especialidad</div>
                                            <div class="info-value"><?php echo e($roleSpecificData['docente']->especialidad ?? 'No especificada'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="info-label">Grado Académico</div>
                                            <div class="info-value"><?php echo e($roleSpecificData['docente']->grado_academico ?? 'No especificado'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="info-label">Años de Experiencia</div>
                                            <div class="info-value"><?php echo e($roleSpecificData['docente']->experiencia ?? 'No especificada'); ?> años</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($roleSpecificData['representante']) && $roleSpecificData['representante']): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Información de Representante</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Ocupación</div>
                                            <div class="info-value"><?php echo e($roleSpecificData['representante']->ocupacion ?? 'No especificada'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Empresa</div>
                                            <div class="info-value"><?php echo e($roleSpecificData['representante']->empresa ?? 'No especificada'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php if(isset($roleSpecificData['estudiantes']) && $roleSpecificData['estudiantes']->count() > 0): ?>
                                    <div class="mt-4">
                                        <h6 class="section-title">Estudiantes a Cargo</h6>
                                        <div class="row">
                                            <?php $__currentLoopData = $roleSpecificData['estudiantes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="card border-primary">
                                                        <div class="card-body">
                                                            <h6 class="card-title"><?php echo e($estudiante->persona->nombre_completo); ?></h6>
                                                            <p class="card-text small text-muted">
                                                                DNI: <?php echo e($estudiante->persona->dni); ?><br>
                                                                Estado: <span class="badge bg-<?php echo e($estudiante->estado == 'Activo' ? 'success' : 'secondary'); ?>"><?php echo e($estudiante->estado); ?></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($roleSpecificData['estudiante']) && $roleSpecificData['estudiante']): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-school me-2"></i>Información de Estudiante</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Código de Estudiante</div>
                                            <div class="info-value"><?php echo e($roleSpecificData['estudiante']->estudiante_id ?? 'No asignado'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Estado Académico</div>
                                            <div class="info-value">
                                                <span class="badge bg-<?php echo e($roleSpecificData['estudiante']->estado == 'Activo' ? 'success' : 'danger'); ?>">
                                                    <?php echo e($roleSpecificData['estudiante']->estado); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/profile/show.blade.php ENDPATH**/ ?>