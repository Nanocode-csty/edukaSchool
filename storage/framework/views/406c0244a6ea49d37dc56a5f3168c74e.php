<div class="card mt-4" style="border: none">
    <div class="card-header-custom">
        <i class="icon-camera mr-2"></i>
        Foto de Identificación
    </div>
    <div class="card-body"
        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

        <div class="row mb-3 align-items-center gap-4 d-flex justify-content-center">

            <!-- DROPZONE -->
            <div class="col-12 col-md-7">
                <div class="dropzone" id="dropzone">
                    <input type="file" name="foto" id="foto" accept="image/*" hidden
                        onchange="previewImage(event)">

                    <div class="dropzone-content text-center">
                        <i class="bi bi-cloud-arrow-up-fill dropzone-icon"></i>
                        <p class="mb-1 fw-semibold">Arrastra y suelta tu imagen aquí</p>
                        <small class="text-muted">o haz clic para seleccionar</small>
                        <div class="text-muted mt-1" style="font-size: 12px;">
                            SVG, PNG o JPG <span style="color:#FF5A6A">(Tamaño
                                Carné)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PREVIEW -->
            <div class="col-12 col-md-4 text-center d-flex justify-content-center">
                <img id="img-preview" src="<?php echo e(asset('imagenes/imgDocente.png')); ?>" alt="Vista previa"
                    class="img-fluid rounded shadow-sm "
                    style="max-height: 190px; border: 1px solid #e0e0e0; padding: 6px; border-radius: 12px !important;">
            </div>
        </div>
    </div>
</div>
<script>
    const dropzone = document.getElementById('dropzone');
    const input = document.getElementById('foto');

    dropzone.addEventListener('click', () => input.click());

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('dragover');

        input.files = e.dataTransfer.files;
        previewImage({
            target: input
        });
    });

    function previewImage(event) {

        //llamamos al div que muestra la imagen
        const preview = document.getElementById('img-preview');

        //SOLO SI HAY UN ARCHIVO SE PROCEDE
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validación estricta del tipo MIME
            if (!file.type.startsWith('image/')) {

                Swal.fire({
                    icon: 'error',
                    title: 'Archivo no permitido',
                    text: 'Solo se permiten archivos de imagen.',
                    showConfirmButton: false,
                    timer: 3200
                });
                // Restablecer la vista previa a la imagen predeterminada
                preview.src = "<?php echo e(asset('imagenes/imgDocente.png')); ?>";
                return;
            }

            //Si no error de tipo de imagen, mostrarla en vista previa
            const reader = new FileReader(); //nuevo objeto FileReader (lector de archivos)
            reader.onload = function(e) { //cuando se carga el archivo, se ejecuta esta función
                preview.src = e.target.result; //se establece la fuente de la imagen de vista previa
            };
            reader.readAsDataURL(file); //leer el archivo como una URL de datos
        }

    }
</script>
<?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/components/persona/formularioFotoIdentificacion.blade.php ENDPATH**/ ?>