{{-- resources/views/ceinformacion/docentes/principal.blade.php --}}
@extends('cplantilla.bprincipal')
@section('titulo', 'Registro y listado de docentes')
@section('contenidoplantilla')

<div class="container-fluid" id="principalContenedor" style="position: relative;">
    @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])
    <div id="contenido-docente">
        {!! $contenido !!}
    </div>


<script>
    window.addEventListener('popstate', function () {
    const url = window.location.pathname;
    cargarVistaSPA(url); // Vuelve a cargar el contenido al volver atrás/adelante
});
</script>

<script>

document.body.addEventListener('click', async function (e) {
    const link = e.target.closest('.no-recargar');
    if (!link) return;

    e.preventDefault();

    const url = link.getAttribute('data-url');
    const loader = document.getElementById('loaderPrincipal');
    const contenedor = document.getElementById('contenido-docente');

    if (!url || !contenedor || !loader) return;

    loader.style.display = 'flex'; // Mostrar loader

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) throw new Error("Error al cargar recurso");

        const html = await response.text();
        contenedor.innerHTML = html;

    } catch (error) {
        console.error('Error al cargar:', error);
        contenedor.innerHTML = '<div class="alert alert-danger">Error inesperado al cargar el contenido.</div>';
    } finally {
        loader.style.display = 'none'; // Ocultar loader siempre
    }
});
</script>
@endsection



<script>
   async function cargarVistaSPA(url) {
    const contenedor1 = document.getElementById('principalContenedor');
    const loader = document.getElementById('loaderPrincipal');

    loader.style.display = 'flex';

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const html = await response.text();
        contenedor1.innerHTML = html;

        // IMPORTANTE: Esperar al render y luego reconfigurar eventos
        setTimeout(() => {
            configurarEnvioFormularioDocente();
            configurarPreviewImagen();
        }, 0);

    } catch (error) {
        contenedor1.innerHTML = '<div class="alert alert-danger">Error al cargar el contenido.</div>';
    } finally {
        loader.style.display = 'none';
    }
}


    // Captura clics SPA
    document.body.addEventListener('click', function (e) {
        const link = e.target.closest('.no-recargar');
        if (link) {
            e.preventDefault();
            const url = link.getAttribute('data-url');
            if (url) cargarVistaSPA(url);
        }
    });

    // Envío AJAX
    function configurarEnvioFormularioDocente() {
    const formulario = document.getElementById('formularioDocente');
    const loader = document.getElementById('loaderPrincipal');
    const contenedor = document.getElementById('contenido-docente');

    if (!formulario) {
        console.warn('⚠️ Formulario de docente no encontrado.');
        return;
    }

    console.log('✅ Formulario encontrado y configurado');

    formulario.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(formulario);
        const url = formulario.getAttribute('action');

        loader.style.display = 'flex';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const html = await response.text();
            contenedor.innerHTML = html;

            // Vuelve a configurar en caso vuelva el mismo formulario
            configurarEnvioFormularioDocente();
            configurarPreviewImagen();
        } catch (error) {
            contenedor.innerHTML = '<div class="alert alert-danger">Error inesperado al registrar.</div>';
        } finally {
            loader.style.display = 'none';
        }
    });
}
</script>
