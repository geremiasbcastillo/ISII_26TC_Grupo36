<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - Sistema ST</title>
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
</head>



<body class="contenedor-centrado">
    <div class="login-box">
        <h2>Registrar Usuario</h2>

        <?php if(isset($validation)): ?>
            <div class="flash flash-error" role="alert">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach($validation as $error):?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_error')): ?>
            <div class="flash flash-error" role="alert">
                <?= session()->getFlashdata('mensaje_error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_exito')): ?>
            <div class="flash flash-success" role="alert">
                <?= session()->getFlashdata('mensaje_exito') ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('guardar_usuario')?>
            
            <div class="input-group">
                <?php echo form_input(['name'=>'nombre', 'id'=>'nombre', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'Nombre']);?>
            </div>

            <div class="input-group">
                <?php echo form_input(['name'=>'apellido', 'id'=>'apellido', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'Apellido']);?>
            </div>

            <div class="input-group">
                <?php echo form_input(['name'=>'dni', 'id'=>'dni', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'DNI']);?>
            </div>

            <div class="input-group">
                <?php echo form_input(['name'=>'correo', 'id'=>'correo', 'type'=>'email', 'class'=>'form-control', 'placeholder'=>'correo@ejemplo.com']);?>
            </div>

            <div class="input-group">
                <?php 
                    $options = [
                        ''            => 'Seleccione un Rol',
                        'Administrador' => 'Administrador',
                        'Tecnico'       => 'Técnico',
                    ];
                    echo form_dropdown('rol', $options, '', ['class' => 'form-control', 'style' => 'background-color: white; color: black;']);
                ?>
            </div>
            
            <div class="input-group">
                <?php echo form_input(['name'=>'contrasena', 'id'=>'contrasena', 'type'=>'password', 'class'=>'form-control', 'placeholder'=>'Contraseña']);?>
            </div>

            <?php echo form_submit('Registrar', 'Registrar');?>
        <?php echo form_close(); ?>

    </div>
</body>
</html>