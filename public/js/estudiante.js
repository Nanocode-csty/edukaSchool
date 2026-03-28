document.addEventListener('DOMContentLoaded', () => {
    const regionSelect = document.getElementById('regionEstudiante');
    const provinciaSelect = document.getElementById('provinciaEstudiante');
    const distritoSelect = document.getElementById('distritoEstudiante');

    // Cargar JSON con fetch
    fetch('/json/peru-regiones.json')
        .then(response => response.json())
        .then(data => {
            window.peruData = data;

            // Poblar regiones
            for (const region in data) {
                const option = document.createElement('option');
                option.value = region;
                option.textContent = region;
                regionSelect.appendChild(option);
            }

            // Si hay valor oldRegion, selecciónalo y dispara cambio
            if (oldRegion) {
                regionSelect.value = oldRegion;
                regionSelect.dispatchEvent(new Event('change'));
            }
        });

    regionSelect.addEventListener('change', () => {
        provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
        distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
        distritoSelect.disabled = true;

        const provincias = window.peruData[regionSelect.value];
        if (provincias) {
            provinciaSelect.disabled = false;
            for (const provincia in provincias) {
                const option = document.createElement('option');
                option.value = provincia;
                option.textContent = provincia;
                provinciaSelect.appendChild(option);
            }
            // Si hay oldProvincia, selecciónala y dispara cambio
            if (oldProvincia) {
                provinciaSelect.value = oldProvincia;
                provinciaSelect.dispatchEvent(new Event('change'));
            }
        } else {
            provinciaSelect.disabled = true;
        }
    });

    provinciaSelect.addEventListener('change', () => {
        distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';

        const distritos = window.peruData[regionSelect.value]?.[provinciaSelect.value];
        if (distritos) {
            distritoSelect.disabled = false;
            distritos.forEach(distrito => {
                const option = document.createElement('option');
                option.value = distrito;
                option.textContent = distrito;
                distritoSelect.appendChild(option);
            });

            // Si hay oldDistrito, selecciónalo
            if (oldDistrito) {
                distritoSelect.value = oldDistrito;
            }
        } else {
            distritoSelect.disabled = true;
        }
    });
});
