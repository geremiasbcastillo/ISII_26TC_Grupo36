<main class="contenedor-centrado">
    <div class="menu-gestion-box">
        <h2 class="titulo-seccion">
            <span class="icono-pantalla">💻</span> EQUIPOS
        </h2>
        
        <?php if (session()->getFlashdata('mensaje_success')): ?>
            <div class="alert alert-success" style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 12px; border-radius: 6px; margin: 20px 0; text-align: center;">
                ✅ <?= session()->getFlashdata('mensaje_success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_error')): ?>
            <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; border-radius: 6px; margin: 20px 0; text-align: center;">
                ⚠️ <?= session()->getFlashdata('mensaje_error') ?>
            </div>
        <?php endif; ?>

        <div class="lista-botones">
            <a href="<?= base_url('agregar') ?>" class="btn-menu-cuadrado">
                REGISTRAR EQUIPO
            </a>

            <a href="<?= base_url('listado') ?>" class="btn-menu-cuadrado">
                VER LISTADO DE EQUIPOS
            </a>

            <a href="<?= base_url('repuestos') ?>" class="btn-menu-cuadrado">
                GESTIONAR REPUESTOS
            </a>

            <a href="<?= base_url('cerrar_sesion') ?>" class="btn-menu-cuadrado">
                Cerrar Sesión
            </a>
        </div>
    </div>
</main>