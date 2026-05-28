<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
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
            <h2 class="registro-titulo">DIAGNÓSTICO DE EQUIPO</h2>

            <?= form_open('guardar_diagnostico', ['class' => 'registro-form']) ?>

                <div class="form-group">
                    <label for="id_equipo">Equipo registrado *</label>
                    <select name="id_equipo" id="id_equipo" class="form-control" required>
                        <option value="" disabled selected>Seleccione un equipo...</option>
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
                    <label for="diagnostico">Diagnóstico *</label>
                    <?= form_textarea([
                        'name'        => 'diagnostico',
                        'id'          => 'diagnostico',
                        'class'       => 'form-control',
                        'rows'        => '5',
                        'placeholder' => 'Describe el diagnóstico realizado al equipo...',
                        'value'       => set_value('diagnostico')
                    ]) ?>
                </div>

                <div class="form-acciones">
                    <button type="button" class="btn-outline" onclick="window.history.back();">Cancelar</button>
                    <?= form_submit('submit', 'Guardar Diagnóstico', ['class' => 'btn-solid']) ?>
                </div>

            <?= form_close() ?>
        </div>
    </div>
</body>
</html>
