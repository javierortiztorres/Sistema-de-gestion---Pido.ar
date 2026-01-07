<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Importar Roles desde CSV</h1>
    <a href="<?= site_url('roles') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="alert alert-info">
            <strong>Formato del CSV:</strong><br>
            El archivo debe tener las siguientes columnas (sin cabecera o con ella, el sistema saltará la primera línea si parece cabecera, pero en este código simple asumimos saltar 1 línea):<br>
            <code>Nombre, Descripcion</code>
        </div>

        <form action="<?= site_url('roles/process-import') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Archivo CSV</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
            </div>

            <button type="submit" class="btn btn-success">Importar</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
