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
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Proveedor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>REP-001</strong></td>
                        <td>Pantalla iPhone 13 OLED</td>
                        <td>Pantallas</td>
                        <td style="color: #28a745; font-weight: bold;">15 unidades</td>
                        <td>$120.00</td>
                        <td>Proveedor A</td>
                    </tr>
                    <tr>
                        <td><strong>REP-002</strong></td>
                        <td>Batería Samsung S21 Ultra</td>
                        <td>Baterías</td>
                        <td style="color: #28a745; font-weight: bold;">30 unidades</td>
                        <td>$45.00</td>
                        <td>Proveedor B</td>
                    </tr>
                    <tr>
                        <td><strong>REP-003</strong></td>
                        <td>Placa de Carga Moto G60</td>
                        <td>Placas</td>
                        <td style="color: #dc3545; font-weight: bold;">3 unidades</td>
                        <td>$15.50</td>
                        <td>Proveedor A</td>
                    </tr>
                    <tr>
                        <td><strong>REP-004</strong></td>
                        <td>Módulo Display Xiaomi Redmi Note 11</td>
                        <td>Pantallas</td>
                        <td style="color: #28a745; font-weight: bold;">22 unidades</td>
                        <td>$65.00</td>
                        <td>Proveedor B</td>
                    </tr>
                    <tr>
                        <td><strong>REP-005</strong></td>
                        <td>Batería iPhone 12 Pro Max</td>
                        <td>Baterías</td>
                        <td style="color: #ffc107; font-weight: bold;">7 unidades</td>
                        <td>$38.00</td>
                        <td>Proveedor A</td>
                    </tr>
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
