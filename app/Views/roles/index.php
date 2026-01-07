<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Roles</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('roles/import-csv') ?>" class="btn btn-sm btn-outline-info me-2">
           <i class="fas fa-file-upload"></i> Importar CSV
        </a>
        <a href="<?= site_url('roles/export-csv') ?>" class="btn btn-sm btn-outline-success me-2">
            <i class="fas fa-file-csv"></i> Exportar CSV
        </a>
        <a href="<?= site_url('roles/create') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus"></i> Nuevo Rol
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= $role['id'] ?></td>
                <td><span class="badge bg-secondary"><?= esc($role['name']) ?></span></td>
                <td><?= esc($role['description']) ?></td>
                <td>
                    <a href="<?= site_url('roles/edit/'.$role['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= site_url('roles/delete/'.$role['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este rol?')" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
