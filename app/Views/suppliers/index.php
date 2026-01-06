<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Proveedores</h1>
    <a href="<?= site_url('suppliers/create') ?>" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-plus"></i> Nuevo Proveedor
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Saldo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?= esc($supplier['name']) ?></td>
                    <td><?= esc($supplier['email']) ?></td>
                    <td><?= esc($supplier['phone']) ?></td>
                    <td class="<?= $supplier['account_balance'] < 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($supplier['account_balance'], 2) ?>
                    </td>
                    <td>
                        <a href="<?= site_url('current-account/supplier/' . $supplier['id']) ?>" class="btn btn-sm btn-info" title="Cuenta Corriente">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </a>
                        <a href="<?= site_url('suppliers/edit/' . $supplier['id']) ?>" class="btn btn-sm btn-primary" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= site_url('suppliers/delete/' . $supplier['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar proveedor?')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
