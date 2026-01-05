<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Productos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('products/create') ?>" class="btn btn-sm btn-outline-primary">Nuevo Producto</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Precio Costo</th>
                <th>Precio Retail</th>
                <th>Precio Mayorista</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= esc($product['code']) ?></td>
                    <td><?= esc($product['name']) ?></td>
                    <td>
                        <?= esc($product['stock_quantity']) ?>
                        <?php if ($product['stock_quantity'] <= $product['min_stock']): ?>
                            <span class="badge bg-warning text-dark">Bajo Stock</span>
                        <?php endif; ?>
                    </td>
                    <td>$<?= esc(number_format($product['cost_price'], 2)) ?></td>
                    <td>$<?= esc(number_format($product['retail_price'], 2)) ?></td>
                    <td>$<?= esc(number_format($product['wholesale_price'], 2)) ?></td>
                    <td>
                        <a href="<?= site_url('products/edit/' . $product['id']) ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <a href="<?= site_url('products/delete/' . $product['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No hay productos registrados.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
