<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">

</head>
<body>
    <?php 
    $validationErrors = session()->getFlashdata('validation') ?? ($validation ?? []); 
    $mensajeError = session()->getFlashdata('mensaje_error') ?? ($mensaje_error ?? null); 
    ?>
    <?php if (!empty($validationErrors) || !empty($mensajeError)): ?>
        <div class="flash flash-error" role="alert">
            <?php if (!empty($mensajeError)): ?>
                <div style="font-weight: bold; margin-bottom: 5px;"><?= esc($mensajeError) ?></div>
            <?php endif; ?>
            <?php if (!empty($validationErrors)): ?>
                <ul>
                    <?php foreach($validationErrors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php $mensajeSuccess = session()->getFlashdata('mensaje_success') ?? ($mensaje_success ?? null); ?>
    <?php if (!empty($mensajeSuccess)): ?>
        <div class="flash flash-success" role="alert">
            <?= esc($mensajeSuccess) ?>
        </div>
    <?php endif; ?>

    <div class="registro-container">
        <div class="registro-card">
            <h2 class="registro-titulo">REPARACIÓN DE EQUIPO</h2>

            <?= form_open('guardar_reparacion', ['class' => 'registro-form', 'id' => 'formReparacion']) ?>

                <div class="form-group">
                    <label for="id_equipo">Equipo diagnosticado *</label>
                    <select name="id_equipo" id="id_equipo" class="form-control" required>
                        <option value="" disabled selected>Seleccione un equipo diagnosticado...</option>
                        <?php if (!empty($equipos)): ?>
                            <?php foreach ($equipos as $equipo): ?>
                                <option value="<?= esc($equipo['id_equipo']) ?>" <?= set_select('id_equipo', $equipo['id_equipo']) ?> >
                                    <?= esc($equipo['nroSerie']) ?> - <?= esc($equipo['tipo_nombre']) ?> / <?= esc($equipo['marca_nombre']) ?> / <?= esc($equipo['modelo_nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Sección de selección de repuestos -->
                <div class="form-group">
                    <label>Repuestos Utilizados *</label>
                    
                    <div class="repuestos-container">
                        <div class="repuestos-selector-wrapper">
                            <label for="id_repuesto" class="repuesto-label-select">Seleccionar Repuesto:</label>
                            <div class="repuestos-input-row">
                                <select id="id_repuesto" class="form-control repuesto-select">
                                    <option value="" disabled selected>Seleccione un repuesto...</option>
                                    <?php if (!empty($repuestos)): ?>
                                        <?php foreach ($repuestos as $repuesto): ?>
                                            <option value="<?= esc($repuesto['id_repuesto']) ?>" data-nombre="<?= esc($repuesto['nombre']) ?>" data-stock="<?= esc($repuesto['cantidad']) ?>">
                                                <?= esc($repuesto['nombre']) ?> (Stock: <?= esc($repuesto['cantidad']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="repuestos-action-row">
                                <div class="repuesto-input-col">
                                    <label for="cantidad_repuesto" class="repuesto-label-cantidad">Cantidad Utilizada:</label>
                                    <input type="number" id="cantidad_repuesto" class="form-control" min="1" value="1" placeholder="Cantidad">
                                </div>
                                <button type="button" class="btn-agregar-repuesto" onclick="agregarRepuesto()">Agregar Repuesto</button>
                            </div>
                        </div>

                        <!-- Lista de repuestos seleccionados -->
                        <div id="repuestosSeleccionados" class="repuestos-seleccionados">
                            <h5 class="repuestos-agregados-titulo">Repuestos Agregados:</h5>
                            <div id="listaRepuestos"></div>
                        </div>
                    </div>

                    <!-- Campo oculto para almacenar los repuestos seleccionados -->
                    <input type="hidden" id="repuestos_json" name="repuestos_json" value="[]">
                </div>

                <div class="form-acciones">
                    <button type="button" class="btn-outline" onclick="window.location.href='<?= base_url('tecnico') ?>';">Cancelar</button>
                    <?= form_submit('submit', 'Guardar Reparación', ['class' => 'btn-solid']) ?>
                </div>

            <?= form_close() ?>
        </div>
    </div>

    <script>
        let repuestosSeleccionados = [];

        function agregarRepuesto() {
            const selectRepuesto = document.getElementById('id_repuesto');
            const cantidadInput = document.getElementById('cantidad_repuesto');
            const idRepuesto = selectRepuesto.value;
            const cantidad = parseInt(cantidadInput.value);

            if (!idRepuesto) {
                alert('Por favor selecciona un repuesto');
                return;
            }

            if (cantidad <= 0) {
                alert('La cantidad debe ser mayor a 0');
                return;
            }

            // Obtener datos del repuesto
            const option = selectRepuesto.options[selectRepuesto.selectedIndex];
            const nombreRepuesto = option.getAttribute('data-nombre');
            const stockDisponible = parseInt(option.getAttribute('data-stock'));

            if (cantidad > stockDisponible) {
                alert(`No hay suficiente stock. Disponible: ${stockDisponible}`);
                return;
            }

            // Verificar si el repuesto ya fue agregado
            const yaAgregado = repuestosSeleccionados.find(r => r.id_repuesto == idRepuesto);
            if (yaAgregado) {
                alert('Este repuesto ya ha sido agregado. Modifica la cantidad si es necesario.');
                return;
            }

            // Agregar a la lista
            repuestosSeleccionados.push({
                id_repuesto: idRepuesto,
                nombre: nombreRepuesto,
                cantidad: cantidad
            });

            actualizarVistaRepuestos();
            selectRepuesto.value = '';
            cantidadInput.value = '1';
        }

        function eliminarRepuesto(idRepuesto) {
            repuestosSeleccionados = repuestosSeleccionados.filter(r => r.id_repuesto != idRepuesto);
            actualizarVistaRepuestos();
        }

        function actualizarVistaRepuestos() {
            const listaDiv = document.getElementById('listaRepuestos');
            const jsonInput = document.getElementById('repuestos_json');

            if (repuestosSeleccionados.length === 0) {
                listaDiv.innerHTML = '<p class="repuesto-vacio-msg">No hay repuestos agregados</p>';
            } else {
                listaDiv.innerHTML = repuestosSeleccionados.map(repuesto => `
                    <div class="repuesto-seleccionado-item">
                        <div class="repuesto-seleccionado-info">
                            <div class="repuesto-seleccionado-nombre">${repuesto.nombre}</div>
                            <div class="repuesto-seleccionado-cantidad">Cantidad utilizada: ${repuesto.cantidad}</div>
                        </div>
                        <button type="button" class="btn-eliminar-repuesto" onclick="eliminarRepuesto(${repuesto.id_repuesto})">Eliminar</button>
                    </div>
                `).join('');
            }

            // Actualizar el JSON oculto
            jsonInput.value = JSON.stringify(repuestosSeleccionados);
        }

        // Validar que haya al menos un repuesto antes de enviar
        document.getElementById('formReparacion').addEventListener('submit', function(e) {
            if (repuestosSeleccionados.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un repuesto');
            }
        });

        // Inicializar la vista
        document.addEventListener('DOMContentLoaded', function() {
            actualizarVistaRepuestos();
        });
    </script>
</body>
</html>
