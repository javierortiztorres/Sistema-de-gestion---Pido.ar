<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Clientes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('clients/import-csv') ?>" class="btn btn-sm btn-outline-info me-2">
           <i class="fas fa-file-upload"></i> Importar CSV
        </a>
        <a href="<?= site_url('clients/export-csv') ?>" class="btn btn-sm btn-outline-success me-2">
            <i class="fas fa-file-csv"></i> Exportar CSV
        </a>
        <a href="<?= site_url('clients/create') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($clients) && is_array($clients)): ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= $client['id'] ?></td>
                    <td><?= esc($client['name']) ?></td>
                    <td><?= $client['type'] == 'retail' ? 'Consumidor Final' : 'Mayorista' ?></td>
                    <td><?= esc($client['email']) ?></td>
                    <td><?= esc($client['phone']) ?></td>
                    <td>
                        <a href="<?= site_url('current-account/client/' . $client['id']) ?>" class="btn btn-sm btn-info" title="Cuenta Corriente">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </a>
                        <a href="<?= site_url('clients/edit/' . $client['id']) ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= site_url('clients/delete/' . $client['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr><?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No hay clientes registrados.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
