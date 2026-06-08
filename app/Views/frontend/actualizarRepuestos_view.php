<?php 
helper('form'); ?>
<head>
    <link href="<?= base_url('public/assets/css/stylesRepuestos.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/miestilo_list.css') ?>" rel="stylesheet">
</head>
<main class="container">
    <h1 class="page-title">ACTUALIZAR REPUESTOS</h1>

    <div class="card">
        <h2 class="section-title">Listado de Repuestos</h2>
        
        <?php if (session()->getFlashdata('mensaje_success')): ?>
            <div class="flash flash-success" role="alert" style="text-align: center;">
                ✅ <?= session()->getFlashdata('mensaje_success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_error')): ?>
            <div class="flash flash-error" role="alert" style="text-align: center;">
                ❌ <?= session()->getFlashdata('mensaje_error') ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="tabla-equipos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Stock Mínimo</th> <th>Cantidad Actual</th>
                        <th>Costo Unitario</th>
                        <th class="col-acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($repuestos)): ?>
                        <?php foreach($repuestos as $repuesto): ?>
                            <tr>
                                <td><strong>REP-<?= str_pad($repuesto['id_repuesto'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                                <td><?= esc($repuesto['nombre']) ?></td>
                                <td><?= esc($repuesto['categoria_nombre'] ?? $repuesto['id_categoria_repuesto']) ?></td>
                                
                                <td style="color: #6c757d; font-weight: 600;">
                                    <?= esc($repuesto['cantidad_minima']) ?>
                                </td>

                                <?php 
                                    // Lógica de colores que ya tenías
                                    $color = '#28a745'; 
                                    if ($repuesto['cantidad'] <= $repuesto['cantidad_minima']) {
                                        $color = '#dc3545'; 
                                    } elseif ($repuesto['cantidad'] <= ($repuesto['cantidad_minima'] + 5)) {
                                        $color = '#ffc107'; 
                                    }
                                ?>
                                <td style="color: <?= $color ?>; font-weight: bold;">
                                    <?= esc($repuesto['cantidad']) ?>
                                </td>
                                
                                <td>$<?= number_format($repuesto['monto'], 2) ?></td>
                                
                                <td class="acciones-celda">
                                    <button type="button" class="btn-sm btn-modificar" 
                                        onclick="abrirModalEditar(
                                            <?= $repuesto['id_repuesto'] ?>, 
                                            '<?= esc(addslashes($repuesto['nombre'])) ?>', 
                                            <?= $repuesto['id_categoria_repuesto'] ?>, 
                                            <?= $repuesto['cantidad'] ?>, 
                                            <?= $repuesto['monto'] ?>,
                                            <?= $repuesto['cantidad_minima'] ?>
                                        )">
                                        ✏️ Modificar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align: center;">No hay repuestos registrados.</td></tr>
                    <?php endif; ?>
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

<div id="modalEditar" class="modal-fondo" style="display: none;">
    <div class="modal-card">
        <h3 class="tabla-titulo">Editar Datos del Repuesto</h3>
        
        <?= form_open('/guardar_edicion') ?>
            
            <input type="hidden" id="edit_id_repuesto" name="id_repuesto">

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_codigo_visual">Código (No editable):</label>
                <input type="text" id="edit_codigo_visual" class="form-control" disabled style="background-color: #e9ecef; cursor: not-allowed;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_nombre">Nombre del Repuesto:</label>
                <input type="text" id="edit_nombre" name="nombre" class="form-control" required>
            </div>

            <div class="form-row" style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="edit_categoria">Categoría:</label>
                    <select id="edit_categoria" name="id_categoria_repuesto" class="form-control" required>
                        <?php if(isset($categorias)): ?>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria_repuesto'] ?>"><?= esc($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_monto">Costo Unitario ($):</label>
                    <input type="number" id="edit_monto" name="monto" class="form-control" min="0" step="0.01" required>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 15px; margin-bottom: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="edit_cantidad">Cantidad Actual:</label>
                    <input type="number" id="edit_cantidad" name="cantidad" class="form-control" min="0" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_cantidad_minima">Stock Mínimo:</label>
                    <input type="number" id="edit_cantidad_minima" name="cantidad_minima" class="form-control" min="0" required>
                </div>
            </div>

            <div class="form-acciones" style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-outline-primary">Guardar Cambios</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
// El JS ahora solo se encarga de abrir el modal y rellenarlo. El guardado lo hace PHP.
function abrirModalEditar(id, nombre, categoria, cantidad, monto, minima) {
    document.getElementById('edit_id_repuesto').value = id;
    document.getElementById('edit_codigo_visual').value = 'REP-' + String(id).padStart(3, '0');
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_categoria').value = categoria;
    document.getElementById('edit_cantidad').value = cantidad;
    document.getElementById('edit_cantidad_minima').value = minima;
    document.getElementById('edit_monto').value = monto;
    
    document.getElementById('modalEditar').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}
</script>