<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo_list.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
</head>

<body class="contenedor" style ="margin-top:120px">
    <div class="tabla-container">
        <div class="tabla-card">
            <h3 class="tabla-titulo"><?= esc($titulo) ?></h3>

            <?php if (session()->getFlashdata('mensaje_success')): ?>
                <div class="flash flash-success" role="alert" style="text-align: center;">
                    ✅ <?= session()->getFlashdata('mensaje_success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('mensaje_error')): ?>
                <div class="flash flash-error" role="alert" style="text-align: center;">
                    ⚠️ <?= session()->getFlashdata('mensaje_error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errores_validation')): ?>
                <div class="flash flash-error" role="alert" style="text-align: center;">
                    <ul style="margin: 0; padding-left: 20px; text-align: left;">
                        <?php foreach (session()->getFlashdata('errores_validation') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="tabla-equipos">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Falla Reportada</th>
                            <th>Fecha de Ingreso</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($equipos)): ?>
                            <?php foreach($equipos as $equipo): ?>
                                <tr>
                                    <td><?= esc($equipo['tipo_nombre']) ?></td>
                                    <td><?= esc($equipo['marca_nombre']) ?></td>
                                    <td><?= esc($equipo['modelo_nombre']) ?></td>
                                    
                                    <td>
                                        <span title="<?= esc($equipo['falla']) ?>">
                                            <?= esc(substr($equipo['falla'], 0, 30)) ?><?= strlen($equipo['falla']) > 30 ? '...' : '' ?>
                                        </span>
                                    </td>
                                    
                                    <td><?= date('d/m/Y', strtotime($equipo['fechaIngreso'])) ?></td>
                                    
                                    <td class="acciones-celda">
                                        <button type="button" class="btn-sm btn-modificar" 
                                            onclick="abrirModalEditar(
                                                <?= $equipo['id_equipo'] ?>, 
                                                '<?= $equipo['id_tipo'] ?>', 
                                                '<?= $equipo['id_marca'] ?>', 
                                                '<?= $equipo['id_modelo'] ?>', 
                                                '<?= esc($equipo['nroSerie']) ?>', 
                                                '<?= htmlspecialchars($equipo['falla'], ENT_QUOTES) ?>'
                                            )">
                                            ✏️ Modificar
                                        </button>
                                        <form action="<?= base_url('eliminar/'.$equipo['id_equipo']) ?>" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este equipo?');">
                                            <button type="submit" class="btn-sm btn-eliminar" style="border: none; cursor: pointer;">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">No hay equipos registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalEditar" class="modal-fondo" style="display: none;">
        <div class="modal-card">
            <h3 class="tabla-titulo">Editar Equipo</h3>
            
            <form id="formEditarEquipo" action="" method="POST">
                <input type="hidden" name="id_equipo" id="edit_id_equipo">

                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="id_tipo" id="edit_id_tipo" class="form-control" >
                            <?php foreach($tipos as $tipo): ?>
                                <option value="<?= $tipo['id_tipo'] ?>"><?= $tipo['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <select name="id_marca" id="edit_id_marca" class="form-control" >
                            <?php foreach($marcas as $marca): ?>
                                <option value="<?= $marca['id_marca'] ?>"><?= $marca['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Modelo</label>
                        <select name="id_modelo" id="edit_id_modelo" class="form-control" >
                            <?php foreach($modelos as $modelo): ?>
                                <option value="<?= $modelo['id_modelo'] ?>" data-marca="<?= $modelo['id_marca'] ?>"><?= $modelo['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nro Serie</label>
                        <input type="text" name="nroSerie" id="edit_nroSerie" class="form-control" >
                    </div>
                </div>

                <div class="form-group">
                    <label>Falla</label>
                    <textarea name="falla" id="edit_falla" class="form-control" rows="2" ></textarea>
                </div>

                <div class="form-acciones" style="margin-top: 20px;">
                    <button type="button" class="btn-outline" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn-solid">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    <div class="form-acciones">
        <button type="button" class="btn-outline" style="width: auto; min-width: 150px;" onclick="window.history.back();">Atrás</button>
    </div>
    <script src="<?= base_url('public/assets/js/modificar_equipos.js') ?>"></script>
</body>