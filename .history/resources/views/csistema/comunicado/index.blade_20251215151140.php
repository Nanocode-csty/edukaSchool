@extends('cplantilla.bprincipal')
@section('titulo', 'Gestión de Comunicados')
@section('contenidoplantilla')
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

        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .btn-primary {
            margin-top: 1rem;
            background: #007bff !important;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            transform: scale(1.01);
        }

        .btn-action-group button,
        .btn-action-group a {
            margin-right: 5px;
        }

        .btn-action-group .btn-link {
            margin-right: 8px;
            padding: 0 6px;
            border: none;
            background: none;
            box-shadow: none;
        }

        .btn-action-group .btn-link:focus {
            outline: none;
            box-shadow: none;
        }

        /* Ajustes para la imagen miniatura */
        .flyer-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e6e6e6;
            display: inline-block;
        }

        /* Modal image preview */
        #previewFlyerImg,
        .editPreviewImg {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e6e6e6;
        }
    </style>

    <div class="container-fluid" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="box_block">

                    <!-- HEADER -->
                    <button class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" type="button"
                        data-toggle="collapse" data-target="#collapseComunicados" aria-expanded="true"
                        aria-controls="collapseComunicados"
                        style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                        <i class="fas fa-bullhorn m-1"></i>&nbsp;Gestión de Comunicados
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex">
                            <div>
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>Administre los comunicados del sistema. Estos comunicados están dirigidos a públicos
                                    específicos.</p>
                                <p><strong>Nota:</strong> Solo los administradores pueden gestionar comunicados.</p>
                            </div>
                        </div>
                    </div>

                    <!-- CUERPO -->
                    <div class="collapse show" id="collapseComunicados">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2"
                            style="background-color: #fcfffc !important">

                            <!-- BOTÓN NUEVO Y BUSCADOR -->
                            <div class="row align-items-center mb-3">
                                <div class="col-md-6 mb-md-0 d-flex justify-content-start">
                                    <!-- BOTÓN que abre modal (no navega) -->
                                    <a id="nuevoComunicadoBtn" class="btn btn-primary w-100" type="button"
                                        data-toggle="modal" data-target="#modalCrearComunicado" style="color:white">
                                        <i class="fa fa-plus mx-2"></i> Nuevo Comunicado
                                    </a>
                                </div>

                                <div class="col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                                    <form id="formBuscar" method="GET" action="{{ route('comunicado.index') }}"
                                        class="w-100">
                                        <div class="input-group">
                                            <input name="buscar" id="inputBuscar" class="form-control mt-3" type="search"
                                                placeholder="Buscar por descripción o público" autocomplete="off"
                                                value="{{ request('buscar') }}" style="border-color: #007bff;">
                                            <button class="btn btn-primary" type="submit"
                                                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- ========== MODAL CREAR (abre con Nuevo Comunicado) ========== -->
                            <div class="modal fade" id="modalCrearComunicado" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <form method="POST" action="{{ route('comunicado.store') }}"
                                        enctype="multipart/form-data" id="formCrearComunicado">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header" style="background:#0A8CB3; color:white;">
                                                <h5 class="modal-title"
                                                    style="font-weight: bold !important; font-size:medium">Nuevo Comunicado
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Cerrar">
                                                    <span aria-hidden="true" style="color:white;">&times;</span>
                                                </button>
                                            </div>


                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Descripción</label>
                                                    <input type="text" name="descripcion" id="descripcion"
                                                        class="form-control @error('descripcion') is-invalid @enderror value="{{ old('descripcion') }}""
                                                        autocomplete="off" required>
                                                    @error('descripcion')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group mt-1">
                                                    <label>Flyer (imagen)</label>

                                                    <input type="file" name="flyer" accept="image/*"
                                                        class="form-control" onchange="previewFlyer(event)">

                                                    <div class="mt-3 text-center">
                                                        <img id="previewFlyerImg"
                                                            src="{{ asset('imagenes/poster (1).png') }}" alt="preview"
                                                            style="max-height: 80px; border-radius: 10px; object-fit: contain;"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <script>
                                                    function previewFlyer(event) {
                                                        let file = event.target.files[0];
                                                        let preview = document.getElementById('previewFlyerImg');
                                                        let defaultImage = "{{ asset('imagenes/poster (1).png') }}";

                                                        if (!file) {
                                                            preview.src = defaultImage;
                                                            return;
                                                        }

                                                        // Validar que sea IMAGEN
                                                        if (!file.type.startsWith('image/')) {

                                                            Swal.fire({
                                                                icon: 'info',
                                                                title: 'Formato Inválido',
                                                                text: 'Solo puedes cargar archivos de tipo imagen.',
                                                                showConfirmButton: false,
                                                                timer: 2100
                                                            });



                                                            event.target.value = ""; // Limpia el input
                                                            preview.src = defaultImage; // Restaura imagen por defecto
                                                            return;
                                                        }

                                                        // Previsualizar imagen
                                                        let reader = new FileReader();
                                                        reader.onload = function(e) {
                                                            preview.src = e.target.result;
                                                        };
                                                        reader.readAsDataURL(file);
                                                    }

                                                    //PARA CUANDO SE CANCELA LA CREACIÓN DEL COMUNICADO
                                                    function limpiarFlyer() {
                                                        let input = document.querySelector('input[name="flyer"]'); // tu input file
                                                        let preview = document.getElementById('previewFlyerImg');
                                                        let defaultImage = "{{ asset('imagenes/poster (1).png') }}";

                                                        // Limpiar input file
                                                        input.value = "";

                                                        // Restaurar imagen por defecto
                                                        preview.src = defaultImage;
                                                    }
                                                </script>


                                                <div class="form-row mt-1">
                                                    <div class="form-group col-md-6">
                                                        <label>Fecha Inicio</label>
                                                        <input type="datetime-local" name="fecha_inicio"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Fecha Fin</label>
                                                        <input type="datetime-local" name="fecha_fin" class="form-control"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="form-group w-100">
                                                    <label>Público</label>
                                                    <select name="publico" class="form-control"
                                                        style="border-color:#F59D24 !important" required>
                                                        <option value="" disabled>Seleccione</option>
                                                        <option value="Representante">Representante</option>
                                                        <option value="Profesor">Profesore</option>
                                                        <option value="General">General</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal" type="button"
                                                    onclick="limpiarFlyer()">Cancelar</button>
                                                <button class="btn btn-secondary" type="submit">Crear Comunicado</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <script>
                                Swal {

                                }
                            </script>

                            <!-- ALERTAS -->
                            @if (session('success'))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({
                                            icon: 'success',
                                            title: '¡Éxito',
                                            text: '{{ session('success') }}',
                                            timer: 2200,
                                            showConfirmButton: false
                                        });
                                    });
                                </script>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="row form-bordered align-items-center"></div>

                            <!-- TABLA -->
                            <div class="table-responsive mt-2">
                                <table class="table-hover table table-custom text-center"
                                    style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                    <thead class="table-hover estilo-info">
                                        <tr>
                                            <th>Estado</th>
                                            <th>Descripción</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Público</th>
                                            <th>Flyer</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($items as $item)
                                            <tr>
                                                @php
                                                    $hoy = \Carbon\Carbon::now();
                                                    $fechaFin = \Carbon\Carbon::parse($item->fecha_fin);
                                                    $fechaInicio = \Carbon\Carbon::parse($item->fecha_inicio);
                                                @endphp

                                                <td>
                                                    @if ($hoy->lessThan($fechaFin))
                                                        @if ($hoy->lessThan($fechaInicio))
                                                            <span class="badge badge-info">PROGRAMADO</span>
                                                        @else
                                                            <span class="badge badge-success">VIGENTE</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning">FINALIZADO</span>
                                                    @endif
                                                </td>

                                                <td>{{ $item->descripcion }}</td>
                                                <td>{{ $item->fecha_inicio }}</td>
                                                <td>{{ $item->fecha_fin }}</td>
                                                <td>
                                                    @if ($item->publico == 'Profesor')
                                                        <span class="badge badge-success">{{ $item->publico }}</span>
                                                    @elseif ($item->publico == 'Representante')
                                                        <span class="badge badge-info">{{ $item->publico }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $item->publico }}</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{-- Columna de imagen --}}
                                                    <div class="text-center" style="min-width: 160px;">

                                                        @php
                                                            // Ruta física del archivo dentro del storage
                                                            $rutaFoto = storage_path(
                                                                'app/public/flyer/' . ($item->flyer_url ?? ''),
                                                            );

                                                            //viene de PHP Verificar si el archivo existe realmente isset verifica si existe y no es nula y file_exists, si está guardada en esa ruta
                                                            $fotoExiste =
                                                                isset($item->flyer_url) && file_exists($rutaFoto);

                                                            //Viene de laravel  Si existe, usarla; de lo contrario, mostrar imagen por defecto
                                                            //asset Genera la URL pública hacia un archivo dentro de public/
                                                            $foto = $fotoExiste
                                                                ? asset('storage/flyer/' . $item->flyer_url)
                                                                : asset('storage/estudiantes/imgDocente.png');
                                                        @endphp
                                                        <!--
                                                                    object-fit: cover hace que la imagen mantenga su proporción y se recorte si es necesario (sin deformarse).
                                                                    draggable="false" Evita que el usuario pueda arrastrar la imagen desde la página.
                                                                    oncontextmenu="return false;" Desactiva el clic derecho sobre la imagen, evitando que alguien la descargue fácilmente o vea sus propiedades.
                                                                    -->
                                                        <img src="{{ $foto }}" alt="Flyer"
                                                            class="img-thumbnail rounded abrirImagen"
                                                            data-img="{{ $foto }}"
                                                            data-caption="{{ $item->descripcion ?? '' }}"
                                                            style="width: 150px; height: 40px; object-fit: cover; cursor: zoom-in;"
                                                            draggable="false" oncontextmenu="return false;"
                                                            title="Ver flyer">

                                                        <!-- ===================== MODAL PROFESIONAL PARA AMPLIAR IMAGEN ===================== -->
                                                        <style>
                                                            /* Asegura backdrop más oscuro y suave */
                                                            .modal-backdrop.show {
                                                                background-color: rgba(0, 0, 0, 0.75) !important;
                                                                /* más opaco */
                                                            }

                                                            /* Card interior donde va la imagen */
                                                            .modal-img-card {
                                                                background: #ffffff;
                                                                border-radius: 12px;
                                                                padding: 18px;
                                                                box-shadow: 0 14px 40px rgba(0, 0, 0, 0.45);
                                                                border: 1px solid rgba(0, 0, 0, 0.08);
                                                                display: inline-block;
                                                                max-width: 100%;
                                                            }

                                                            /* Contenedor para centrar y dar un poco de separación */
                                                            .modal-img-wrap {
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                padding: 10px;
                                                            }

                                                            /* Imagen ampliada: mantén proporción y no se salga de la pantalla */
                                                            #imagenAmpliada {
                                                                display: block;
                                                                max-height: 80vh;
                                                                /* no sobrepasa la pantalla */
                                                                max-width: 100%;
                                                                object-fit: contain;
                                                                /* mantener proporción sin recortar */
                                                                border-radius: 8px;
                                                                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
                                                            }

                                                            /* Botones superiores (cerrar + descargar) */
                                                            .modal-img-actions {
                                                                position: absolute;
                                                                top: 12px;
                                                                right: 18px;
                                                                z-index: 1052;
                                                                /* sobre el contenido del modal */
                                                                display: flex;
                                                                gap: 8px;
                                                            }

                                                            /* Estilo botón icon (círculo blanco con sombra) */
                                                            .modal-img-btn {
                                                                background: rgba(255, 255, 255, 0.95);
                                                                border: none;
                                                                padding: 8px 10px;
                                                                border-radius: 50%;
                                                                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
                                                                cursor: pointer;
                                                            }

                                                            /* Pequeña animación */
                                                            .modal-img-btn:hover {
                                                                transform: scale(1.04);
                                                            }

                                                            /* Asegura que el modal-dialog ocupe suficiente anchura sin ser demasiado grande */
                                                            @media (min-width: 992px) {
                                                                .modal-dialog.modal-xl-centered {
                                                                    max-width: 1000px;
                                                                }
                                                            }
                                                        </style>

                                                        <!-- Modal (único, reutilizable) -->
                                                        <div class="modal fade" id="modalImagenProfesional"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-md modal-lg-centered"
                                                                role="document">
                                                                <div class="modal-content"
                                                                    style="background: transparent; border: none;">
                                                                    <div class="position-relative d-flex justify-content-center align-items-center"
                                                                        style="min-height: 50vh;">

                                                                        <!-- acciones (cerrar + descargar) -->
                                                                        <div class="modal-img-actions">
                                                                            <button type="button" class="modal-img-btn"
                                                                                id="btnDescargarImagen"
                                                                                title="Descargar Flyer">
                                                                                <i class="fas fa-download"></i>

                                                                            </button>

                                                                            <button type="button" class="modal-img-btn"
                                                                                data-dismiss="modal" aria-label="Cerrar"
                                                                                id="btnCerrarModal" title="Cerrar">
                                                                                <i class="far fa-window-close"></i>
                                                                            </button>
                                                                        </div>

                                                                        <!-- Card blanca que contiene la imagen -->
                                                                        <div class="modal-img-card">
                                                                            <div class="modal-img-wrap">
                                                                                <img id="imagenAmpliada" src=""
                                                                                    alt="Imagen ampliada">
                                                                            </div>
                                                                            <!-- pie opcional con caption -->
                                                                            <div class="text-center mt-2"
                                                                                id="captionImagen"
                                                                                style="color:#444; font-weight:600;"></div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- ===================== SCRIPT: abrir modal dinámicamente (Bootstrap4/5 fallback) ===================== -->
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {

                                                                // Función para abrir modal con una URL y texto (caption opcional)
                                                                function abrirImagenProfesional(url, caption = '') {
                                                                    const img = document.getElementById('imagenAmpliada');
                                                                    const cap = document.getElementById('captionImagen');
                                                                    img.src = url;
                                                                    cap.textContent = caption || '';

                                                                    // Actualiza el link de descarga
                                                                    const btnDesc = document.getElementById('btnDescargarImagen');
                                                                    btnDesc.onclick = function() {
                                                                        // forzar descarga: creamos un <a download>
                                                                        const a = document.createElement('a');
                                                                        a.href = url;
                                                                        // intenta sacar un nombre legible
                                                                        const parts = url.split('/');
                                                                        let filename = parts[parts.length - 1] || 'imagen';
                                                                        a.download = filename;
                                                                        document.body.appendChild(a);
                                                                        a.click();
                                                                        a.remove();
                                                                    };

                                                                    // Mostrar modal: intenta Bootstrap 5, si falla usa jQuery/Bootstrap4
                                                                    try {
                                                                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                                                            const modal = new bootstrap.Modal(document.getElementById('modalImagenProfesional'), {
                                                                                keyboard: true
                                                                            });
                                                                            modal.show();
                                                                            return;
                                                                        }
                                                                    } catch (err) {
                                                                        // continue to fallback
                                                                    }

                                                                    // Fallback (jQuery)
                                                                    if (window.jQuery && typeof jQuery('#modalImagenProfesional').modal === 'function') {
                                                                        jQuery('#modalImagenProfesional').modal('show');
                                                                    }
                                                                }

                                                                // Delegación: detectar clics en miniaturas con clase .abrirImagen
                                                                document.body.addEventListener('click', function(e) {
                                                                    const tgt = e.target.closest('.abrirImagen');
                                                                    if (!tgt) return;
                                                                    e.preventDefault();
                                                                    const url = tgt.dataset.img || tgt.getAttribute('src');
                                                                    const caption = tgt.dataset.caption || tgt.alt || '';
                                                                    if (url) abrirImagenProfesional(url, caption);
                                                                });

                                                                // Cerrar modal con el botón custom (compatible con BS4/5)
                                                                document.getElementById('btnCerrarModal').addEventListener('click', function() {
                                                                    try {
                                                                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                                                            const modalEl = document.getElementById('modalImagenProfesional');
                                                                            const instance = bootstrap.Modal.getInstance(modalEl);
                                                                            if (instance) instance.hide();
                                                                            else {
                                                                                // si no hay instancia, crearla y ocultar
                                                                                const m = new bootstrap.Modal(modalEl);
                                                                                m.hide();
                                                                            }
                                                                            return;
                                                                        }
                                                                    } catch (err) {}
                                                                    if (window.jQuery) jQuery('#modalImagenProfesional').modal('hide');
                                                                });

                                                            });
                                                        </script>



                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    No hay comunicados disponibles.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- PAGINACIÓN SI EXISTE -->
                            @if (method_exists($items, 'links'))
                                <div class="mt-3">
                                    {{ $items->links() }}
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- JS: preview, eliminar confirm y compatibilidad modal -->
    <script>
        // Preview para editar (por item)
        function previewEditFlyer(event, id) {
            const img = document.getElementById('editPreview' + id);
            if (!event.target.files || !event.target.files[0]) return;
            img.src = URL.createObjectURL(event.target.files[0]);
        }

        // Eliminar con SweetAlert
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btnEliminar')) {
                const btn = e.target.closest('.btnEliminar');
                const id = btn.dataset.id;

                Swal.fire({
                    title: '¿Eliminar comunicado?',
                    text: 'Esta acción no se puede revertir.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Crear formulario y enviarlo
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/comunicado/${id}`;
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content');
                        form.innerHTML = `
                        <input type="hidden" name="_token" value="${token}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });

        // Fallback: asegurar que el botón nuevo abra el modal aunque la app use data-bs (BS5) o data-toggle (BS4)
        document.getElementById('btnNuevoComunicado').addEventListener('click', function() {
            // Try Bootstrap 5
            try {
                const modal = new bootstrap.Modal(document.getElementById('modalCrearComunicado'));
                modal.show();
            } catch (err) {
                // Fallback a jQuery/Bootstrap4
                try {
                    $('#modalCrearComunicado').modal('show');
                } catch (err2) {
                    // no-op
                }
            }
        });

        // Loader handling (si tienes loaderPrincipal)
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            const contenido = document.getElementById('contenido-principal');
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';

            // Al enviar formulario crear, muestra loader (opcional)
            const formCrear = document.getElementById('formCrearComunicado');
            if (formCrear) {
                formCrear.addEventListener('submit', function() {
                    if (loader) loader.style.display = 'flex';
                });
            }
        });
    </script>

@endsection
