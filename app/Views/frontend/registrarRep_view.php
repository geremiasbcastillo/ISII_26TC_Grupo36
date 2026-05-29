<head>
    <link href="<?= base_url('public/assets/css/stylesRepuestos.css') ?>" rel="stylesheet">
</head>
<main class="container">
    
    <h1 class="page-title">REGISTRAR REPUESTO</h1>

    <section class="card form-section">
        <form action="#" method="POST">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="codigo">Código:</label>
                    <input type="text" id="codigo" name="codigo" placeholder="Ingrese código del repuesto">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre del repuesto:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombre del repuesto">
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria">
                        <option value="">Seleccione una categoría</option>
                        <option value="1">Pantallas</option>
                        <option value="2">Baterías</option>
                        <option value="3">Placas</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="proveedor">Proveedor:</label>
                    <select id="proveedor" name="proveedor">
                        <option value="">Seleccione un proveedor</option>
                        <option value="1">Proveedor A</option>
                        <option value="2">Proveedor B</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" placeholder="Ingrese cantidad disponible" min="0">
                </div>
                <div class="form-group">
                    <label for="costo">Costo Repuesto:</label>
                    <input type="number" id="costo" name="costo" placeholder="0" min="0" step="0.01">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-outline-primary">Registrar Repuesto</button>
                <button type="button" class="btn btn-outline-secondary">Cancelar</button>
            </div>
        </form>
    </section>

    <section class="card list-section">
        <h2 class="section-title">Lista de Repuestos Registrados</h2>
        
        <div class="list-placeholders">
            <div class="placeholder-row"></div>
            <div class="placeholder-row"></div>
            <div class="placeholder-row"></div>
        </div>
    </section>

</main>
