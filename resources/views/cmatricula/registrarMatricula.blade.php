 <style>
     .form-bordered {
         margin: 0;
         border: none;
         padding-top: 15px;
         padding-bottom: 15px;
         border-bottom: 1px dashed #eaedf1;
     }

     .card-body.info {
         background: #f3f3f3;
         border-bottom: 1px solid rgba(0, 0, 0, .125);
         border-top: 1px solid rgba(0, 0, 0, .125);
         color: #F59D24;
     }

     .card-body.info p {
         margin-bottom: 0px;
         font-family: "Quicksand", sans-serif;
         font-weight: 600;
         color: #004a92;
     }

     .mensaje-estudiante {
         padding: 15px;
         border-radius: 5px;
         margin-bottom: 15px;
         font-weight: bold;
     }

     .mensaje-nuevo {
         background-color: #fff3cd;
         border: 1px solid #ffeaa7;
         color: #856404;
     }

     .mensaje-existente {
         background-color: #d1ecf1;
         border: 1px solid #bee5eb;
         color: #0c5460;
     }
 </style>
 <style>
     .estilo-info {
         margin-bottom: 0px;
         font-family: "Quicksand", sans-serif;
         font-weight: 700;
         font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
     }

     @media (max-width: 576px) {
         .margen-movil {
             margin-left: -29px !important;
             margin-right: -29px !important;
         }

         .margen-movil-2 {
             margin: 0 !important;
             padding: 0 !important;
         }
     }
 </style>

 <!-- Búsqueda de Estudiante -->
 <div class="card " style="border: none">
     <div
         style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
         <i class="icon-graduation mr-2"></i>
         Datos del estudiante
     </div>

     <div class="card-body"
         style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
         <form method="GET" action="{{ route('matriculas.create') }}">
             <div class="row form-group">
                 <label class="col-md-2 col-form-label">Buscar por N.° de DNI <span
                         style="color: #FF5A6A">(*)</span></label>
                 <div class="col-md-6">
                     <div class="input-group">
                         <input type="text" class="form-control" name="dni" value="{{ $dni ?? '' }}"
                             placeholder="Ingrese un N° de DNI" maxlength="8" required autocomplete="off"
                             style="border: 1px solid #F59617 !important">
                         <button class="btn rounded-start-0" type="submit"
                             style="background-color: #F59617; color:white; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; font-weight:bold">
                             <i class="fas fa-search mx-1"></i> Buscar
                         </button>
                     </div>
                 </div>
                 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                 <div class="col-md-4">
                     @if ($error)
                         <!--SI EL ESTUDIANTE NO EXISTE O ESTÁ INHABILIDATO-->
                         <script>
                             document.addEventListener('DOMContentLoaded', function() {
                                 Swal.fire({
                                     icon: 'info',
                                     title: 'Estimado usuario',
                                     text: "{{ $error }}",
                                     confirmButtonColor: '#28a745',
                                     timer: 1200,
                                     showConfirmButton: false
                                 });
                             });
                         </script>
                     @endif

                     @if ($estudiante && !$error)
                         <button id="btnNuevaBusqueda" class="btn btn-info" style="font-weight: bold">
                             <i class="fas fa-search mx-1"></i> Nueva búsqueda
                         </button>
                     @endif

                     <script>
                         $(document).ready(function() {
                             $("#btnNuevaBusqueda").on("click", function(e) {
                                 e.preventDefault();

                                 $.ajax({
                                     url: "{{ route('matriculas.create') }}",
                                     type: "GET",
                                     beforeSend: function() {
                                         // Aquí puedes poner tu loader reutilizable
                                         $("#contenido").html("<p>Cargando...</p>");
                                     },
                                     success: function(response) {
                                         // Inserta la vista devuelta en tu contenedor SPA
                                         $("#contenido").html(response);
                                     },
                                     error: function(xhr) {
                                         console.error(xhr.responseText);
                                         alert("Ocurrió un error al cargar la búsqueda.");
                                     }
                                 });
                             });
                         });
                     </script>


                     @if ($dni && !$estudiante && !$error)
                         <div class="text-muted">
                             <i class="fas fa-info-circle"></i> Estudiante encontrado
                         </div>
                     @endif
                 </div>
             </div>
         </form>
     </div>
 </div>

 @if ($estudiante && !$error)
     <!-- Información del Estudiante -->
     <div class="card" style="border: none">
         <div
             style="background: #E8F5E8; color: #2E7D32; font-weight: bold; border: 2px solid #A5D6A7; border-bottom: 2px solid #A5D6A7; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
             Datos del Estudiante
         </div>

         <div class="card-body"
             style="border: 2px solid #A5D6A7; border-top: none; border-radius: 0px 0px 4px 4px !important;">
             <!-- Mensaje diferenciado -->
             @if ($mensaje)
                 <div class="mensaje-estudiante {{ $esNuevo ? 'mensaje-nuevo' : 'mensaje-existente' }}">
                     <i class="fas fa-info-circle"></i> {{ $mensaje }}
                 </div>
             @endif

             <div class="row">
                 <div class="col-md-6">
                     <h6><strong>Información Personal:</strong></h6>
                     <ul class="list-unstyled">
                         <li><strong>DNI:</strong> {{ $estudiante->dni }}</li>
                         <li><strong>Apellidos:</strong> {{ $estudiante->apellidos }}
                         </li>
                         <li><strong>Nombres:</strong> {{ $estudiante->nombres }}</li>
                         <li><strong>Teléfono:</strong>
                             {{ $estudiante->telefono ?? 'No registrado' }}</li>
                         <li><strong>Email:</strong>
                             {{ $estudiante->email ?? 'No registrado' }}</li>
                     </ul>
                 </div>
                 <div class="col-md-6">
                     <h6><strong>Información Académica:</strong></h6>
                     <ul class="list-unstyled">
                         <li><strong>Grado a matricular:</strong>
                             {{ $grado->nombre ?? 'No disponible' }}</li>
                         <li><strong>Nivel:</strong>
                             {{ $grado->nivel->nombre ?? 'No disponible' }}</li>
                         <li><strong>Estado estudiante:</strong>
                             <span class="badge bg-success"
                                 style="border: none; color:white; font-weight:bold">{{ Str::upper($estudiante->estado) }}</span>
                         </li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>

     <!-- Formulario de Matrícula -->
     <form method="POST" action="{{ route('matriculas.store') }}" novalidate>
         @csrf

         <!-- Campos ocultos -->
         <input type="hidden" name="estudiante_id" value="{{ $estudiante->estudiante_id }}">
         <input type="hidden" name="idGrado" value="{{ $grado->grado_id ?? '' }}">

         <div class="card" style="border: none">
             <div
                 style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                 Datos de la Matrícula
             </div>

             <div class="card-body"
                 style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                 <div class="row form-group">
                     <label class="col-md-2 col-form-label">Grado <span style="color: #FF5A6A">(*)</span></label>
                     <div class="col-md-4">
                         <input type="text" class="form-control"
                             value="{{ $grado->nivel->nombre ?? '' }} - {{ $grado->nombre ?? '' }}" readonly
                             style="background-color: #f8f9fa; cursor: not-allowed;">
                         <div class="form-text">El grado se asigna automáticamente según
                             el historial del estudiante.</div>
                     </div>

                     <label class="col-md-2 col-form-label">Sección <span style="color: #FF5A6A">(*)</span></label>
                     <div class="col-md-4">
                         <select class="form-control @error('idSeccion') is-invalid @enderror" name="idSeccion"
                             required>
                             <option value="">Seleccione una sección</option>
                             @foreach ($secciones as $seccionData)
                                 <option value="{{ $seccionData['seccion']->seccion_id }}"
                                     {{ old('idSeccion') == $seccionData['seccion']->seccion_id ? 'selected' : '' }}>
                                     {{ $seccionData['seccion']->nombre }}
                                     ({{ $seccionData['disponibles'] }} cupos
                                     disponibles)
                                 </option>
                             @endforeach
                         </select>
                         @error('idSeccion')
                             <div class="invalid-feedback d-block text-start">
                                 {{ $message }}</div>
                         @enderror
                         <div class="form-text">{{ $secciones->count() }} secciones con
                             cupos disponibles.</div>
                     </div>
                 </div>

                 <div class="row form-group">
                     <label class="col-md-2 col-form-label">Observaciones</label>
                     <div class="col-md-10">
                         <textarea class="form-control @error('observaciones') is-invalid @enderror" name="observaciones" rows="3"
                             placeholder="Ingrese observaciones adicionales (opcional)" maxlength="500">{{ old('observaciones') }}</textarea>
                         @error('observaciones')
                             <div class="invalid-feedback d-block text-start">
                                 {{ $message }}</div>
                         @enderror
                         <div class="form-text">Máximo 500 caracteres.</div>
                     </div>
                 </div>
             </div>
         </div>

         <!-- Botones de acción -->
         <div class="d-flex justify-content-between align-items-center">
             <div>
                 <a href="{{ route('matriculas.index') }}" class="btn btn-secondary">
                     <i class="fas fa-arrow-left"></i> Volver al listado
                 </a>

             </div>
             <div>
                 <button type="submit" class="btn btn-primary" style="background: #F59617 !important; border: none;">
                     <i class="fas fa-save"></i> Registrar Matrícula
                 </button>
             </div>
         </div>
     </form>
 @endif

 @if (!$estudiante && !$dni)
     <!-- Mensaje inicial -->
     <div class="alert alert-info">
         <i class="fas fa-info-circle"></i>
         <strong>Instrucciones:</strong>
         <h5>
             Ingrese el N.° de DNI del estudiante en el campo de
             búsqueda para comenzar el proceso de matrícula.
         </h5>

     </div>
 @endif


 <!-- Mensajes de notificación -->
 @if (session('success'))
     <div class="alert alert-success alert-dismissible fade show position-fixed"
         style="top: 20px; right: 20px; z-index: 1050;" role="alert">
         <i class="fas fa-check-circle"></i>
         {{ session('success') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
     </div>
 @endif

 @if (session('error'))
     <div class="alert alert-danger alert-dismissible fade show position-fixed"
         style="top: 20px; right: 20px; z-index: 1050;" role="alert">
         <i class="fas fa-exclamation-circle"></i>
         {{ session('error') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
     </div>
 @endif

 @if ($errors->any())
     <div class="alert alert-danger alert-dismissible fade show position-fixed"
         style="top: 20px; right: 20px; z-index: 1050;" role="alert">
         <i class="fas fa-exclamation-circle"></i>
         <strong>Error en el formulario:</strong>
         <ul class="mb-0 mt-1">
             @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
         </ul>
         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
     </div>
 @endif

 <style>
     .btn-primary {
         background: #F59617 !important;
         border: none;
         transition: background-color 0.2s ease, transform 0.1s ease;
     }

     .btn-primary:hover {
         background-color: #F59619 !important;
         transform: scale(1.01);
     }
 </style>

 <script>
     // Script simple para auto-hide mensajes
     setTimeout(function() {
         document.querySelectorAll('.alert').forEach(alert => {
             if (alert.classList.contains('fade')) {
                 alert.style.display = 'none';
             }
         });
     }, 5000);

     // Validación simple del DNI
     document.addEventListener('DOMContentLoaded', function() {
         const dniInput = document.querySelector('input[name="dni"]');
         if (dniInput) {
             dniInput.addEventListener('input', function() {
                 this.value = this.value.replace(/[^0-9]/g, '');
                 if (this.value.length > 8) {
                     this.value = this.value.slice(0, 8);
                 }
             });
         }
     });
 </script>
