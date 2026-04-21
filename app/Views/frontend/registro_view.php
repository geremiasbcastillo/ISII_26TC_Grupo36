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
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-size: 14px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach($validation as $error):?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_error')): ?>
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                <?= session()->getFlashdata('mensaje_error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_exito')): ?>
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                <?= session()->getFlashdata('mensaje_exito') ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('guardar_usuario')?>
            
            <div class="input-group">
                <?php echo form_input(['name'=>'nombre', 'id'=>'nombre', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'Nombre', 'required'=>'required']);?>
            </div>

            <div class="input-group">
                <?php echo form_input(['name'=>'apellido', 'id'=>'apellido', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'Apellido', 'required'=>'required']);?>
            </div>

            <div class="input-group">
                <?php echo form_input(['name'=>'dni', 'id'=>'dni', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'DNI', 'required'=>'required']);?>
            </div>

            <div class="input-group">
                <?php echo form_input(['name'=>'correo', 'id'=>'correo', 'type'=>'email', 'class'=>'form-control', 'placeholder'=>'correo@ejemplo.com', 'required'=>'required']);?>
            </div>

            <div class="input-group">
                <?php 
                    $options = [
                        ''            => 'Seleccione un Rol',
                        'Administrador' => 'Administrador',
                        'Tecnico'       => 'Técnico',
                    ];
                    echo form_dropdown('rol', $options, '', ['class' => 'form-control', 'required' => 'required', 'style' => 'background-color: white; color: black;']);
                ?>
            </div>
            
            <div class="input-group">
                <?php echo form_input(['name'=>'contrasena', 'id'=>'contrasena', 'type'=>'password', 'class'=>'form-control', 'placeholder'=>'Contraseña', 'required'=>'required']);?>
            </div>

            <?php echo form_submit('Registrar', 'Registrar');?>
        <?php echo form_close(); ?>

    </div>
</body>
</html>