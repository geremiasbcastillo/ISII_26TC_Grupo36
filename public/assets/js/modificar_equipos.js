// Abre el modal y llena los campos con los datos de la fila
function abrirModalEditar(id, tipo, marca, modelo, serie, falla) {
    // 1. Buscamos el formulario por su ID
    const formulario = document.getElementById('formEditarEquipo');
    
    // 2. Le inyectamos la URL correcta (ej: localhost/tu-proyecto/actualizar/5)
    // Ajusta la URL base según cómo tengas configurado tu CodeIgniter
    formulario.action = 'actualizar/' + id; 

    // 3. Llenamos los campos
    document.getElementById('edit_id_equipo').value = id;
    document.getElementById('edit_id_tipo').value = tipo;
    document.getElementById('edit_id_marca').value = marca;
    document.getElementById('edit_id_modelo').value = modelo;
    document.getElementById('edit_nroSerie').value = serie;
    document.getElementById('edit_falla').value = falla;
    
    document.getElementById('modalEditar').style.display = 'flex';
}

// Cierra el modal
function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Validación del lado del cliente antes de enviar el formulario
document.getElementById('formEditarEquipo').addEventListener('submit', function(e) {
    const tipo = document.getElementById('edit_id_tipo').value;
    const marca = document.getElementById('edit_id_marca').value;
    const modelo = document.getElementById('edit_id_modelo').value;
    const serie = document.getElementById('edit_nroSerie').value.trim();
    const falla = document.getElementById('edit_falla').value.trim();

    let errores = [];

    if (!tipo) {
        errores.push("Debe seleccionar el tipo de equipo.");
    }
    if (!marca) {
        errores.push("Debe seleccionar la marca del equipo.");
    }
    if (!modelo) {
        errores.push("Debe seleccionar el modelo del equipo.");
    }
    if (!serie) {
        errores.push("Debe ingresar el número de serie.");
    } else {
        if (serie.length < 3) {
            errores.push("El número de serie debe tener al menos 3 caracteres.");
        }
        if (serie.length > 20) {
            errores.push("El número de serie no puede exceder los 20 caracteres.");
        }
    }

    // Falla no es obligatoria, pero si se escribe algo debe tener al menos 10 caracteres
    if (falla !== "" && falla.length < 10) {
        errores.push("La falla debe tener al menos 10 caracteres.");
    }

    if (errores.length > 0) {
        e.preventDefault(); // Cancela el envío del formulario
        alert(errores.join("\n"));
    }
});