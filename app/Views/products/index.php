<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Productos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('products/export-csv') ?>" class="btn btn-sm btn-outline-success me-2">
            <i class="fas fa-file-csv"></i> Exportar CSV
        </a>
        <a href="<?= site_url('products/create') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Código</th>
                <th>Categoría</th>
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
                <tr class="<?= ($product['stock_quantity'] <= $product['min_stock']) ? 'table-danger' : '' ?>">
                    <td><?= esc($product['code']) ?></td>
                    <td><?= !empty($product['category_name']) ? esc($product['category_name']) : '<span class="text-muted">-</span>' ?></td>
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
                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                data-bs-toggle="modal" 
                                data-bs-target="#adjustStockModal" 
                                data-id="<?= $product['id'] ?>"
                                data-name="<?= esc($product['name']) ?>"
                                data-stock="<?= $product['stock_quantity'] ?>">
                            Ajustar Stock
                        </button>
                        <a href="<?= site_url('products/delete/' . $product['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No hay productos registrados.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" id="adjustStockForm">
          <div class="modal-header">
            <h5 class="modal-title">Ajustar Stock</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Producto: <strong id="adjustProductName"></strong></p>
            <p>Stock Actual: <span id="adjustProductStock"></span></p>
            
            <div class="mb-3">
                <label for="change_amount" class="form-label">Cantidad a Ajustar (+/-)</label>
                <input type="number" class="form-control" id="change_amount" name="change_amount" required placeholder="Ej: 5 (agregar) o -2 (quitar)">
                <div class="form-text">Use números positivos para agregar o negativos para quitar.</div>
            </div>
            
            <div class="mb-3">
                <label for="reason" class="form-label">Motivo</label>
                <input type="text" class="form-control" id="reason" name="reason" required placeholder="Ej: Rotura, Auditoría, Compra extra">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Ajuste</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
    var adjustStockModal = document.getElementById('adjustStockModal');
    adjustStockModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var stock = button.getAttribute('data-stock');

        var form = adjustStockModal.querySelector('#adjustStockForm');
        form.action = '<?= site_url('products/adjust-stock/') ?>' + id;

        adjustStockModal.querySelector('#adjustProductName').textContent = name;
        adjustStockModal.querySelector('#adjustProductStock').textContent = stock;
        adjustStockModal.querySelector('#change_amount').value = '';
        adjustStockModal.querySelector('#reason').value = '';
    });
</script>
<?= $this->endSection() ?>
