<head>
    <link href="<?= base_url('public/assets/css/stylesRepuestos.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/miestilo_list.css') ?>" rel="stylesheet">
</head>
<main class="container">
    <h1 class="page-title">ACTUALIZAR REPUESTOS</h1>

    <div class="card">
        <h2 class="section-title">Listado de Repuestos</h2>
        
        <!-- Alerta de éxito dinámica -->
        <div id="alertSuccess" style="display: none; background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb; text-align: center;">
            ✅ El repuesto fue actualizado correctamente en el sistema.
        </div>

        <div class="table-responsive">
            <table class="tabla-equipos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Proveedor</th>
                        <th class="col-acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-repuestos-body">
                    <tr id="row-REP-001">
                        <td><strong class="rep-codigo">REP-001</strong></td>
                        <td class="rep-nombre">Pantalla iPhone 13 OLED</td>
                        <td class="rep-categoria" data-val="1">Pantallas</td>
                        <td class="rep-cantidad" style="color: #28a745; font-weight: bold;">15</td>
                        <td class="rep-costo" data-val="120.00">$120.00</td>
                        <td class="rep-proveedor" data-val="1">Proveedor A</td>
                        <td class="acciones-celda">
                            <button type="button" class="btn-sm btn-modificar" 
                                onclick="abrirModalEditar('REP-001', 'Pantalla iPhone 13 OLED', 1, 15, 120.00, 1)">
                                ✏️ Modificar
                            </button>
                        </td>
                    </tr>
                    <tr id="row-REP-002">
                        <td><strong class="rep-codigo">REP-002</strong></td>
                        <td class="rep-nombre">Batería Samsung S21 Ultra</td>
                        <td class="rep-categoria" data-val="2">Baterías</td>
                        <td class="rep-cantidad" style="color: #28a745; font-weight: bold;">30</td>
                        <td class="rep-costo" data-val="45.00">$45.00</td>
                        <td class="rep-proveedor" data-val="2">Proveedor B</td>
                        <td class="acciones-celda">
                            <button type="button" class="btn-sm btn-modificar" 
                                onclick="abrirModalEditar('REP-002', 'Batería Samsung S21 Ultra', 2, 30, 45.00, 2)">
                                ✏️ Modificar
                            </button>
                        </td>
                    </tr>
                    <tr id="row-REP-003">
                        <td><strong class="rep-codigo">REP-003</strong></td>
                        <td class="rep-nombre">Placa de Carga Moto G60</td>
                        <td class="rep-categoria" data-val="3">Placas</td>
                        <td class="rep-cantidad" style="color: #dc3545; font-weight: bold;">3</td>
                        <td class="rep-costo" data-val="15.50">$15.50</td>
                        <td class="rep-proveedor" data-val="1">Proveedor A</td>
                        <td class="acciones-celda">
                            <button type="button" class="btn-sm btn-modificar" 
                                onclick="abrirModalEditar('REP-003', 'Placa de Carga Moto G60', 3, 3, 15.50, 1)">
                                ✏️ Modificar
                            </button>
                        </td>
                    </tr>
                    <tr id="row-REP-004">
                        <td><strong class="rep-codigo">REP-004</strong></td>
                        <td class="rep-nombre">Módulo Display Xiaomi Redmi Note 11</td>
                        <td class="rep-categoria" data-val="1">Pantallas</td>
                        <td class="rep-cantidad" style="color: #28a745; font-weight: bold;">22</td>
                        <td class="rep-costo" data-val="65.00">$65.00</td>
                        <td class="rep-proveedor" data-val="2">Proveedor B</td>
                        <td class="acciones-celda">
                            <button type="button" class="btn-sm btn-modificar" 
                                onclick="abrirModalEditar('REP-004', 'Módulo Display Xiaomi Redmi Note 11', 1, 22, 65.00, 2)">
                                ✏️ Modificar
                            </button>
                        </td>
                    </tr>
                    <tr id="row-REP-005">
                        <td><strong class="rep-codigo">REP-005</strong></td>
                        <td class="rep-nombre">Batería iPhone 12 Pro Max</td>
                        <td class="rep-categoria" data-val="2">Baterías</td>
                        <td class="rep-cantidad" style="color: #ffc107; font-weight: bold;">7</td>
                        <td class="rep-costo" data-val="38.00">$38.00</td>
                        <td class="rep-proveedor" data-val="1">Proveedor A</td>
                        <td class="acciones-celda">
                            <button type="button" class="btn-sm btn-modificar" 
                                onclick="abrirModalEditar('REP-005', 'Batería iPhone 12 Pro Max', 2, 7, 38.00, 1)">
                                ✏️ Modificar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-actions" style="margin-top: 30px;">
            <a href="<?= base_url('repuestos') ?>" class="btn btn-outline-secondary" style="text-decoration: none; text-align: center;">
                Volver al Menú de Repuestos
            </a>
        </div>
    </div>
</main>

<!-- Modal de edición translúcido (Mismo estilo que listadoEquipos) -->
<div id="modalEditar" class="modal-fondo" style="display: none;">
    <div class="modal-card">
        <h3 class="tabla-titulo">Editar Datos del Repuesto</h3>
        
        <form id="formEditarRepuesto" onsubmit="guardarCambios(event)">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_codigo">Código (Identificador):</label>
                <input type="text" id="edit_codigo" class="form-control" readonly style="background-color: #e9ecef; cursor: not-allowed;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_nombre">Nombre del Repuesto:</label>
                <input type="text" id="edit_nombre" class="form-control" required placeholder="Ingrese nombre del repuesto">
            </div>

            <div class="form-row" style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="edit_categoria">Categoría:</label>
                    <select id="edit_categoria" class="form-control" required>
                        <option value="1">Pantallas</option>
                        <option value="2">Baterías</option>
                        <option value="3">Placas</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_proveedor">Proveedor:</label>
                    <select id="edit_proveedor" class="form-control" required>
                        <option value="1">Proveedor A</option>
                        <option value="2">Proveedor B</option>
                    </select>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 15px; margin-bottom: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="edit_cantidad">Cantidad:</label>
                    <input type="number" id="edit_cantidad" class="form-control" min="0" required placeholder="Cantidad">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_costo">Costo Unitario ($):</label>
                    <input type="number" id="edit_costo" class="form-control" min="0" step="0.01" required placeholder="0.00">
                </div>
            </div>

            <div class="form-acciones" style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-outline-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalEditar(codigo, nombre, categoriaVal, cantidad, costo, proveedorVal) {
    document.getElementById('edit_codigo').value = codigo;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_categoria').value = categoriaVal;
    document.getElementById('edit_cantidad').value = cantidad;
    document.getElementById('edit_costo').value = costo;
    document.getElementById('edit_proveedor').value = proveedorVal;
    document.getElementById('modalEditar').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

function guardarCambios(event) {
    event.preventDefault();
    
    const codigo = document.getElementById('edit_codigo').value;
    const nombre = document.getElementById('edit_nombre').value;
    const categoriaSelect = document.getElementById('edit_categoria');
    const categoriaVal = categoriaSelect.value;
    const categoriaText = categoriaSelect.options[categoriaSelect.selectedIndex].text;
    const cantidad = parseInt(document.getElementById('edit_cantidad').value);
    const costo = parseFloat(document.getElementById('edit_costo').value).toFixed(2);
    const proveedorSelect = document.getElementById('edit_proveedor');
    const proveedorVal = proveedorSelect.value;
    const proveedorText = proveedorSelect.options[proveedorSelect.selectedIndex].text;

    // Actualizamos visualmente la fila de la tabla correspondiente
    const fila = document.getElementById('row-' + codigo);
    if (fila) {
        fila.querySelector('.rep-nombre').innerText = nombre;
        
        const catTd = fila.querySelector('.rep-categoria');
        catTd.innerText = categoriaText;
        catTd.setAttribute('data-val', categoriaVal);

        const cantTd = fila.querySelector('.rep-cantidad');
        cantTd.innerText = cantidad;
        // Ajuste de color según la cantidad
        if (cantidad <= 3) {
            cantTd.style.color = '#dc3545';
        } else if (cantidad <= 10) {
            cantTd.style.color = '#ffc107';
        } else {
            cantTd.style.color = '#28a745';
        }

        const costTd = fila.querySelector('.rep-costo');
        costTd.innerText = '$' + costo;
        costTd.setAttribute('data-val', costo);

        const provTd = fila.querySelector('.rep-proveedor');
        provTd.innerText = proveedorText;
        provTd.setAttribute('data-val', proveedorVal);

        // Actualizamos los atributos onclick del botón modificar para que contenga los nuevos valores
        const btnModificar = fila.querySelector('.btn-modificar');
        btnModificar.setAttribute('onclick', `abrirModalEditar('${codigo}', '${nombre.replace(/'/g, "\\'")}', ${categoriaVal}, ${cantidad}, ${costo}, ${proveedorVal})`);
    }

    // Cerramos el modal
    cerrarModal();

    // Mostramos la alerta de éxito
    const alert = document.getElementById('alertSuccess');
    alert.style.display = 'block';
    
    // Ocultar la alerta automáticamente después de 4 segundos
    setTimeout(() => {
        alert.style.display = 'none';
    }, 4000);
}
</script>
