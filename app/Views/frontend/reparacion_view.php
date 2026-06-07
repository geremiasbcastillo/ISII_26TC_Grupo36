<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
    <style>
        .repuestos-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .repuesto-item {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        
        .repuesto-info {
            flex: 1;
        }
        
        .repuesto-nombre {
            font-weight: bold;
            color: #333;
        }
        
        .repuesto-stock {
            font-size: 0.9em;
            color: #666;
            margin-top: 3px;
        }
        
        .repuesto-cantidad {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .repuesto-cantidad input {
            width: 70px;
            padding: 5px;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            text-align: center;
        }
        
        .btn-agregar-repuesto {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s;
        }
        
        .btn-agregar-repuesto:hover {
            background-color: #218838;
        }
        
        .btn-eliminar-repuesto {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.85em;
            transition: background-color 0.3s;
        }
        
        .btn-eliminar-repuesto:hover {
            background-color: #c82333;
        }
        
        .repuestos-seleccionados {
            margin-top: 20px;
        }
        
        .repuesto-seleccionado-item {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .repuesto-seleccionado-info {
            flex: 1;
        }
        
        .repuesto-seleccionado-nombre {
            font-weight: bold;
            color: #155724;
        }
        
        .repuesto-seleccionado-cantidad {
            font-size: 0.9em;
            color: #155724;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <?php if(isset($validation) && !empty($validation)): ?>
        <div class="alert alert-danger" style="color: #ff4d4d; margin-bottom: 15px;">
            <ul>
                <?php foreach($validation as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('mensaje_error')): ?>
        <div class="alert alert-danger my-4" role="alert">
            <?= session()->getFlashdata('mensaje_error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('mensaje_success')): ?>
        <div class="alert alert-success my-4" role="alert">
            <?= session()->getFlashdata('mensaje_success') ?>
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

                <div class="form-group">
                    <label for="observaciones">Observaciones de la Reparación</label>
                    <?= form_textarea([
                        'name'        => 'observaciones',
                        'id'          => 'observaciones',
                        'class'       => 'form-control',
                        'rows'        => '4',
                        'placeholder' => 'Describe los detalles de la reparación realizada...',
                        'value'       => set_value('observaciones')
                    ]) ?>
                </div>

                <!-- Sección de selección de repuestos -->
                <div class="form-group">
                    <label>Repuestos Utilizados *</label>
                    
                    <div class="repuestos-container">
                        <div style="margin-bottom: 15px;">
                            <label for="id_repuesto" style="display: block; margin-bottom: 8px;">Seleccionar Repuesto:</label>
                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <select id="id_repuesto" class="form-control" style="flex: 1;">
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

                            <div style="display: flex; gap: 10px; align-items: flex-end;">
                                <div style="flex: 1;">
                                    <label for="cantidad_repuesto" style="display: block; margin-bottom: 5px;">Cantidad Utilizada:</label>
                                    <input type="number" id="cantidad_repuesto" class="form-control" min="1" value="1" placeholder="Cantidad">
                                </div>
                                <button type="button" class="btn-agregar-repuesto" onclick="agregarRepuesto()">Agregar Repuesto</button>
                            </div>
                        </div>

                        <!-- Lista de repuestos seleccionados -->
                        <div id="repuestosSeleccionados" class="repuestos-seleccionados">
                            <h5 style="color: #333; margin-bottom: 10px;">Repuestos Agregados:</h5>
                            <div id="listaRepuestos"></div>
                        </div>
                    </div>

                    <!-- Campo oculto para almacenar los repuestos seleccionados -->
                    <input type="hidden" id="repuestos_json" name="repuestos_json" value="[]">
                </div>

                <div class="form-acciones">
                    <button type="button" class="btn-outline" onclick="window.history.back();">Cancelar</button>
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
                listaDiv.innerHTML = '<p style="color: #999; font-style: italic;">No hay repuestos agregados</p>';
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
