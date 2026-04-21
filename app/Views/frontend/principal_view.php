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
        <a href="<?= base_url('equipos/agregar') ?>" class="btn-largo btn-agregar">
            <span class="icono">➕</span> Agregar Nuevo Equipo
        </a>

        <a href="<?= base_url('equipos/modificar') ?>" class="btn-largo btn-modificar">
            <span class="icono">📝</span> Modificar Datos de Equipo
        </a>

        <a href="<?= base_url('equipos/eliminar') ?>" class="btn-largo btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
            <span class="icono">🗑️</span> Eliminar Equipo del Sistema
        </a>
    </div>
</div>