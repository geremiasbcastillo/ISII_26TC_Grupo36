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