<head>
    <link href="<?= base_url('public/assets/css/stylesRepuestos.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/miestilo_list.css') ?>" rel="stylesheet">
</head>
<main class="container">
    <h1 class="page-title">STOCK ACTUAL DE REPUESTOS</h1>

    <div class="card">
        <div class="table-responsive">
            <table class="tabla-equipos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Cantidad Mínima</th>
                        <th>Cantidad </th>
                        <th>Costo Unitario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($repuestos) && is_array($repuestos)): ?>
                        
                        <?php foreach($repuestos as $repuesto): ?>
                            <tr>
                                <td><strong>REP-<?= str_pad($repuesto['id_repuesto'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                                
                                <td><?= esc($repuesto['nombre']) ?></td>
                                
                                <td><?= esc($repuesto['categoria_nombre'] ?? $repuesto['id_categoria_repuesto']) ?></td>
                                
                                <?php 
                                    $color = '#28a745'; // Verde por defecto (Stock saludable)
                                    if ($repuesto['cantidad'] <= $repuesto['cantidad_minima']) {
                                        $color = '#dc3545'; // Rojo (Alerta: Stock crítico o agotado)
                                    } elseif ($repuesto['cantidad'] <= ($repuesto['cantidad_minima'] + 2)) {
                                        $color = '#ffc107'; // Amarillo (Advertencia: Cerca del mínimo)
                                    }
                                ?>

                                <td><?= esc($repuesto['cantidad_minima']) ?></td>
                                
                                <td style="color: <?= $color ?>; font-weight: bold;">
                                    <?= esc($repuesto['cantidad']) ?> unidades
                                </td>
                                
                                <td>$<?= number_format($repuesto['monto'], 2, '.', ',') ?></td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                                No hay repuestos registrados en el sistema actualmente.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions" style="margin-top: 30px;">
            <a href="<?= base_url('repuestos') ?>" class="btn btn-outline-secondary" style="text-decoration: none; text-align: center;">
                Volver al Menú de Repuestos
            </a>
        </div>
    </div>
</main>