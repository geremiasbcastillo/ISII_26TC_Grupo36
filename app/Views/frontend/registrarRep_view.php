<?php 
helper('form'); 

// 1. Preparar opciones para el dropdown de Categorías
$opcionesCategorias = ['' => 'Seleccione una categoría...'];
if (isset($categorias)) {
    foreach ($categorias as $categoria) {
        // Asumiendo que tu tabla de categorías tiene un 'id_categoria_repuesto' y un 'nombre'
        $opcionesCategorias[$categoria['id_categoria_repuesto']] = $categoria['nombre']; 
    }
}
?>

<head>
    <link href="<?= base_url('public/assets/css/stylesRepuestos.css') ?>" rel="stylesheet">
</head>

<body>
    <?php if(isset($validation)): ?>
        <div class="flash flash-error" role="alert">
            <ul>
                <?php foreach($validation as $error):?>
                    <li><?= esc($error) ?></li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="registro-container">
        
        <div class="registro-card">
            <h2 class="registro-titulo">REGISTRAR REPUESTO</h2>
            
            <?php if (session()->getFlashdata('mensaje_error')): ?>
                <div class="flash flash-error" role="alert">
                    <?= session()->getFlashdata('mensaje_error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('mensaje_success')): ?>
                <div class="flash flash-success" role="alert">
                    <?= session()->getFlashdata('mensaje_success') ?>
                </div>
            <?php endif; ?>

            <?= form_open('guardar_repuesto', ['class' => 'registro-form']) ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre del repuesto *</label>
                        <?= form_input([
                            'name'        => 'nombre', 
                            'id'          => 'nombre', 
                            'type'        => 'text', 
                            'class'       => 'form-control', 
                            'placeholder' => 'Ej: Motor Ventilador 1/4 HP'
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <label for="id_categoria_repuesto">Categoría *</label>
                        <?= form_dropdown('id_categoria_repuesto', $opcionesCategorias, '', [
                            'id'    => 'id_categoria_repuesto',
                            'class' => 'form-control'
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cantidad">Cantidad inicial en stock *</label>
                        <?= form_input([
                            'name'        => 'cantidad', 
                            'id'          => 'cantidad', 
                            'type'        => 'number', 
                            'class'       => 'form-control',
                            'min'         => '0',
                            'placeholder' => 'Unidades disponibles'
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <label for="cantidad_minima">Stock mínimo (Alerta) *</label>
                        <?= form_input([
                            'name'        => 'cantidad_minima', 
                            'id'          => 'cantidad_minima', 
                            'type'        => 'number', 
                            'class'       => 'form-control',
                            'min'         => '0',
                            'placeholder' => 'Unidades para alertar'
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="width: 50%;">
                        <label for="monto">Monto (Precio unitario) *</label>
                        <?= form_input([
                            'name'        => 'monto', 
                            'id'          => 'monto', 
                            'type'        => 'number', 
                            'class'       => 'form-control',
                            'min'         => '0',
                            'step'        => '0.01',
                            'placeholder' => '0.00'
                        ]) ?>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="button" class="btn-outline" onclick="window.history.back();">Cancelar</button>
                    <?= form_submit('submit', 'Registrar Repuesto', ['class' => 'btn-solid']) ?>
                </div>

            <?= form_close() ?>
        </div>

    </div>

</body>