<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Usuarios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('users/export-csv') ?>" class="btn btn-sm btn-outline-success me-2">
            <i class="fas fa-file-csv"></i> Exportar CSV
        </a>
        <a href="<?= site_url('users/create') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Última Actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td>
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="badge bg-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-info text-dark">Empleado</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $user['updated_at'] ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '-' ?></td>
                    <td>
                        <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if ($user['id'] != session()->get('id')): ?>
                            <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar este usuario?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
