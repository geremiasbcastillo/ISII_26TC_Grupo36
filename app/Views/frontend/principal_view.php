<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('public/assets/css/miestilo.css') ?>" rel="stylesheet">
</head>

<div class="gestion-container">
    <h2 class="titulo-gestion">Gestión de Equipos</h2>
    
    <div class="menu-acciones">
        <a href="<?= base_url('agregar') ?>" class="btn-largo btn-agregar">
            <span class="icono">➕</span> Registrar Nuevo Equipo
        </a>

        <a href="<?= base_url('listado') ?>" class="btn-largo btn-listar">
            <span class="icono">📝</span> Ver listado de Equipos
        </a>

    </div>
</div>