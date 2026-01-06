<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categorías</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('categories/create') ?>" class="btn btn-sm btn-outline-primary">Nueva Categoría</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Padre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($categories) && is_array($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= esc($category['id']) ?></td>
                    <td><?= esc($category['name']) ?></td>
                    <td><?= !empty($category['parent_name']) ? esc($category['parent_name']) : '<span class="text-muted">-</span>' ?></td>
                    <td><?= esc($category['description']) ?></td>
                    <td>
                        <a href="<?= site_url('categories/edit/' . $category['id']) ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <a href="<?= site_url('categories/delete/' . $category['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar esta categoría? Si tiene productos, estos quedarán sin categoría.')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No hay categorías registradas.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
