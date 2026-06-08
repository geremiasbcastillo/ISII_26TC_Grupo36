<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
</head>
<body>
    <?php $validationErrors = session()->getFlashdata('validation') ?? ($validation ?? []); ?>
    <?php if (!empty($validationErrors)): ?>
        <div class="flash flash-error" role="alert">
            <ul>
                <?php foreach($validationErrors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php $mensajeError = session()->getFlashdata('mensaje_error') ?? ($mensaje_error ?? null); ?>
    <?php if (!empty($mensajeError)): ?>
        <div class="flash flash-error" role="alert">
            <?= esc($mensajeError) ?>
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
                    <label for="analisis">Análisis (Diagnóstico) *</label>
                    <?= form_textarea([
                        'name'        => 'analisis',
                            'id'          => 'analisis',
                            'class'       => 'form-control',
                            'rows'        => '5',
                            'placeholder' => 'Describe el análisis o diagnóstico realizado al equipo...',
                            'required'    => 'required',
                            'value'       => set_value('analisis')
                    ]) ?>
                </div>

                <div class="form-group">
                    <label for="solucion">Solución Propuesta</label>
                    <?= form_textarea([
                        'name'        => 'solucion',
                        'id'          => 'solucion',
                        'class'       => 'form-control',
                        'rows'        => '3',
                        'placeholder' => 'Describe la solución propuesta (opcional)...',
                        'value'       => set_value('solucion')
                    ]) ?>
                </div>

                <div class="form-group">
                    <label for="costo_estimado">Costo Estimado ($)</label>
                    <?= form_input([
                        'name'        => 'costo_estimado',
                        'id'          => 'costo_estimado',
                        'type'        => 'number',
                        'step'        => '0.01',
                        'class'       => 'form-control',
                        'placeholder' => 'Ej: 15000.50',
                        'value'       => set_value('costo_estimado')
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
