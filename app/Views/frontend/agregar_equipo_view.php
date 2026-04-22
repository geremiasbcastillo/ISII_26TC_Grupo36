<?php 
helper('form'); 

$opcionesTipos = ['' => 'Seleccione un tipo...'];
if (isset($tipos)) {
    foreach ($tipos as $tipo) {
        $opcionesTipos[$tipo['id_tipo']] = $tipo['nombre'];
    }
}

$opcionesMarcas = ['' => 'Seleccione una marca...'];
if (isset($marcas)) {
    foreach ($marcas as $marca) {
        $opcionesMarcas[$marca['id_marca']] = $marca['nombre'];
    }
}

$opcionesModelos = ['' => 'Seleccione un modelo...'];
if (isset($modelos)) {
    foreach ($modelos as $modelo) {
        $opcionesModelos[$modelo['id_modelo']] = $modelo['nombre'];
    }
}
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
    </head>

    <body>
        <?php if(isset($validation)): ?>
            <div class="alert alert-danger" style="color: #ff4d4d; margin-bottom: 15px;">
                <ul>
                    <?php foreach($validation as $error):?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="registro-container">
            <div class="registro-card">
                <h2 class="registro-titulo">REGISTRAR EQUIPO</h2>
                
                <?php if (session()->getFlashdata('mensaje_error')): ?>
                    <div class="alert alert-danger my-4" role="alert">
                        <?= session()->getFlashdata('mensaje_error') ?>
                    </div>
                <?php endif; ?>

                <?= form_open('registrar_equipo', ['class' => 'registro-form']) ?>
                    
                    <div class="form-group" style="background-color: #f0f7ff; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
                        <label for="dni_cliente" style="color: #004494;">DNI del Cliente *</label>
                        <?= form_input([
                            'name'        => 'dni_cliente', 
                            'id'          => 'dni_cliente', 
                            'type'        => 'number', 
                            'class'       => 'form-control', 
                            'placeholder' => 'Ingrese el DNI para verificar...'
                        ]) ?>
                        <small style="color: #666; margin-top: 5px;">El sistema verificará que el cliente exista antes de registrar el equipo.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_tipo">Tipo de equipo *</label>
                            <?= form_dropdown('id_tipo', $opcionesTipos, '', [
                                'id'       => 'id_tipo',
                                'class'    => 'form-control'
                            ]) ?>
                        </div>
                        <div class="form-group">
                            <label for="nroSerie">Número de Serie *</label>
                            <?= form_input([
                                'name'        => 'nroSerie', 
                                'id'          => 'nroSerie', 
                                'type'        => 'number', 
                                'class'       => 'form-control', 
                                'placeholder' => 'N° de serie del fabricante'
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_marca">Marca *</label>
                            <?= form_dropdown('id_marca', $opcionesMarcas, '', [
                                'id'       => 'id_marca',
                                'class'    => 'form-control'
                            ]) ?>
                        </div>
                    
                        <div class="form-group">
                            <label for="id_modelo">Modelo *</label>
                            <select id="id_modelo" name="id_modelo" class="form-control" >
                                <option value="" disabled selected>Seleccione primero una marca...</option>
                                
                                <?php foreach($modelos as $modelo): ?>
                                    <option value="<?= $modelo['id_modelo'] ?>" data-marca="<?= $modelo['id_marca'] ?>" style="display: none;">
                                        <?= $modelo['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="falla">Falla reportada (Problema) *</label>
                        <?= form_textarea([
                            'name'        => 'falla', 
                            'id'          => 'falla', 
                            'class'       => 'form-control', 
                            'rows'        => '3',
                            'placeholder' => 'Descripción detallada de la falla...'
                        ]) ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fechaIngreso">Fecha de ingreso *</label>
                            <?= form_input([
                                'name'        => 'fechaIngreso', 
                                'id'          => 'fechaIngreso', 
                                'type'        => 'date', 
                                'class'       => 'form-control'
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-acciones">
                        <button type="button" class="btn-outline" onclick="window.history.back();">Cancelar</button>
                        <?= form_submit('submit', 'Registrar Equipo', ['class' => 'btn-solid']) ?>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
        <script src="<?= base_url('public/assets/js/equipos.js') ?>"></script>

    </body>
</html>