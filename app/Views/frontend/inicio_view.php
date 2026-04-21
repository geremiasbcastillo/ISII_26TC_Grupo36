<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
</head>
<?php if(isset($validation)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach($validation as $error):?>
                <li><?= esc($error) ?></li>
            <?php endforeach;?>
        </ul>
    </div>
<?php endif; ?>
<body class="">
    <div class="login-box">
        <h2>Iniciar Sesión</h2>
        
        <?php if (session()->getFlashdata('mensaje_error')): ?>
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                <?= session()->getFlashdata('mensaje_error') ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('verificar_usuario')?>
            <div class="input-group">
                <?php echo form_input(['name'=>'correo', 'id'=>'correo', 'type'=>'text', 'class'=>'form-control', 'placeholder'=>'andresbarberan@gmail.com']);?>
            </div>
            
            <div class="input-group">
                <?php echo form_input(['name'=>'contrasena', 'id'=>'contrasena', 'type'=>'password', 'class'=>'form-control', 'placeholder'=>'••••••••']);?>
            </div>

            <?php echo form_submit('Iniciar sesion', 'Iniciar sesion');?>
        <?php echo form_close(); ?>

        <div class="separator">¿No tienes cuenta?</div>

        <a href="<?= base_url('registro') ?>">
            <button class="btn btn-register">Crear cuenta nueva</button>
        </a>
    </div>
</body>
</html>