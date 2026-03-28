<div class="margen-movil-2">
    <div class="card" style="border: none">
        <div class="card-header-custom">
            <i class="icon-location-pin mr-2"></i>
            Información de Residencia
        </div>
        <div class="card-body"
            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

            <div class="row mb-3 align-items-center">
                <label class="col-12 col-md-2 col-form-label">
                    Región
                </label>
                <div class="col-12 col-md-10">
                    <select id="region" name="region" class="form-control @error('region') is-invalid @enderror">
                        <option value="" disabled {{ old('region') == '' ? 'selected' : '' }}>
                            Seleccionar
                            Región</option>
                    </select>
                    @error('region')
                        <div class="invalid-feedback d-block text-start">{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-12 col-md-2 col-form-label">
                    Provincia
                </label>
                <div class="col-12 col-md-4">
                    <select id="provincia" name="provincia"
                        class="form-control @error('provincia') is-invalid @enderror" disabled>
                        <option value="" disabled {{ old('provincia') == '' ? 'selected' : '' }}>
                            Seleccionar Provincia</option>
                    </select>
                    @error('provincia')
                        <div class="invalid-feedback d-block text-start">{{ $message }}
                        </div>
                    @enderror
                </div>

                <label class="col-12 col-md-2 col-form-label">
                    Distrito
                </label>
                <div class="col-12 col-md-4">
                    <select id="distrito" name="distrito" class="form-control @error('distrito') is-invalid @enderror"
                        disabled>
                        <option value="" disabled {{ old('distrito') == '' ? 'selected' : '' }}>
                            Seleccionar
                            Distrito</option>
                    </select>
                    @error('distrito')
                        <div class="invalid-feedback d-block text-start">{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <x-form.field name="calle" label="Avenida o calle" type="text" maxlength="200" />
            <x-form.field name="referencia" label="Referencia" maxlength="20" />

        </div>
    </div>
</div>

<!--CARGAR REGIONES, PROVINCIAS Y DISTRITOS-->
<script>
    document.addEventListener('DOMContentLoaded', async () => {

        const region = document.getElementById('region');
        const provincia = document.getElementById('provincia');
        const distrito = document.getElementById('distrito');

        const oldRegion = "{{ old('region') }}";
        const oldProvincia = "{{ old('provincia') }}";
        const oldDistrito = "{{ old('distrito') }}";

        await cargarRegiones();

        if (oldRegion) {
            region.value = oldRegion;
            await cargarProvincias();
        }

        if (oldProvincia) {
            provincia.value = oldProvincia;
            await cargarDistritos();
        }

        if (oldDistrito) {
            distrito.value = oldDistrito;
        }

        region.addEventListener('change', async () => {
            await cargarProvincias();
            distrito.innerHTML = '<option value="">Seleccionar Distrito</option>';
        });

        provincia.addEventListener('change', cargarDistritos);

    });

    async function cargarRegiones() {

        const region = document.getElementById('region');

        const res = await fetch('/regiones');
        const data = await res.json();

        region.innerHTML = '<option value="">Seleccionar Región</option>';

        data.forEach(r => {
            region.innerHTML += `<option value="${r.idRegion}">${r.nombre}</option>`;
        });

    }

    async function cargarProvincias() {

        const region = document.getElementById('region').value;
        const provincia = document.getElementById('provincia');

        provincia.disabled = true;

        if (!region) return;

        const res = await fetch(`/provincias/${region}`);
        const data = await res.json();

        provincia.innerHTML = '<option value="">Seleccionar Provincia</option>';

        data.forEach(p => {
            provincia.innerHTML += `<option value="${p.idProvincia}">${p.nombre}</option>`;
        });

        provincia.disabled = false;

    }

    async function cargarDistritos() {

        const provincia = document.getElementById('provincia').value;
        const distrito = document.getElementById('distrito');

        distrito.disabled = true;

        if (!provincia) return;

        const res = await fetch(`/distritos/${provincia}`);
        const data = await res.json();

        distrito.innerHTML = '<option value="">Seleccionar Distrito</option>';

        data.forEach(d => {
            distrito.innerHTML += `<option value="${d.idDistrito}">${d.nombre}</option>`;
        });

        distrito.disabled = false;

    }
</script>

<!--VALIDACIONES DE CAMPOS-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = {

            region: document.getElementById('region'),
            provincia: document.getElementById('provincia'),
            distrito: document.getElementById('distrito'),
            calle: document.getElementById('calle'),

        };

        function setInvalid(input, message) {
            input.classList.add('is-invalid');
            let feedback = input.parentElement.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block text-start';
                input.parentElement.appendChild(feedback);
            }
            feedback.textContent = message;

        }

        function clearInvalid(input) {
            //removemos la etiqueta de INVÁLIDO y anadimos la de VÁLIDO
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            const feedback = input.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.remove();

        }

        inputs.region.addEventListener('change', function() {
            if (!this.value) {
                setInvalid(this, 'Seleccione una opción.');
            } else {
                clearInvalid(this);
            }
        });

        inputs.provincia.addEventListener('change', function() {
            if (!this.value) {
                setInvalid(this, 'Seleccione una opción.');
            } else {
                clearInvalid(this);
            }
        });

        inputs.distrito.addEventListener('change', function() {
            if (!this.value) {
                setInvalid(this, 'Seleccione una opción.');
            } else {
                clearInvalid(this);
            }
        });

        inputs.calle.addEventListener('input', function() {
            if (this.value.length < 2 || this.value.length > 50) {
                setInvalid(this, 'Debe tener entre 2 y 50 caracteres.');
            } else {
                clearInvalid(this);
            }
        });


    });
</script>
