<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Importar Clientes desde CSV</h1>
    <a href="<?= site_url('clients') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="alert alert-info">
            <strong>Formato del CSV:</strong><br>
            El archivo debe tener las siguientes columnas:<br>
            <code>Nombre, Tipo, Email, Telefono, Direccion</code>
        </div>

        <form action="<?= site_url('clients/process-import') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Archivo CSV</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
            </div>

            <button type="submit" class="btn btn-success">Importar</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
