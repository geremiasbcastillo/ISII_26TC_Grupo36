
document.addEventListener("DOMContentLoaded", function() {
    const selectMarca = document.getElementById('id_marca');
    const selectModelo = document.getElementById('id_modelo');
    const opcionesModelo = selectModelo.querySelectorAll('option');

    selectMarca.addEventListener('change', function() {
        const idMarcaSeleccionada = this.value;

        // 1. Reiniciar el selector de modelo al cambiar la marca
        selectModelo.value = "";
        selectModelo.options[0].text = "Seleccione un modelo...";

        // 2. Recorrer todas las opciones de modelos
        opcionesModelo.forEach(function(opcion) {
            // Ignoramos la primera opción (el placeholder)
            if (opcion.value === "") return;

            // 3. Mostrar u ocultar según la marca
            if (opcion.getAttribute('data-marca') === idMarcaSeleccionada) {
                opcion.style.display = 'block'; // Lo mostramos
                opcion.disabled = false;        // Lo habilitamos
            } else {
                opcion.style.display = 'none';  // Lo ocultamos
                opcion.disabled = true;         // Lo deshabilitamos por seguridad
            }
        });
    });
});