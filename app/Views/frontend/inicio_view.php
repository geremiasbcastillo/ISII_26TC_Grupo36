<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="contenedor-centrado">
        <div class="login-box">
            <h2>Iniciar Sesión</h2>
            
            <?php 
            $validationErrors = session()->getFlashdata('validation') ?? ($validation ?? []); 
            $mensajeError = session()->getFlashdata('mensaje_error') ?? ($mensaje_error ?? null); 
            ?>
            <?php if (!empty($validationErrors) || !empty($mensajeError)): ?>
                <div class="flash flash-error" role="alert" style="text-align: center;">
                    <?php if (!empty($mensajeError)): ?>
                        <div style="font-weight: bold; margin-bottom: 5px;"><?= esc($mensajeError) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($validationErrors)): ?>
                        <ul style="margin: 0; padding: 0; list-style-type: none;">
                            <?php foreach($validationErrors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
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
    </div>
</body>
</html>