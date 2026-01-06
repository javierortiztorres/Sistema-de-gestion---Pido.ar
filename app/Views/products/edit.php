<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Producto</h1>
</div>

<div class="row">
    <div class="col-md-8 order-md-1">
        <form action="<?= site_url('products/update/' . $product['id']) ?>" method="post">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="code" class="form-label">Código</label>
                    <input type="text" class="form-control" id="code" name="code" value="<?= old('code', $product['code']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category_id" class="form-label">Categoría</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Seleccionar Categoría</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= old('category_id', $product['category_id']) == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $product['name']) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción <span class="text-muted">(Opcional)</span></label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $product['description']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="cost_price" class="form-label">Precio Costo</label>
                    <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" value="<?= old('cost_price', $product['cost_price']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="retail_price" class="form-label">Precio Retail</label>
                    <input type="number" step="0.01" class="form-control" id="retail_price" name="retail_price" value="<?= old('retail_price', $product['retail_price']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="wholesale_price" class="form-label">Precio Mayorista</label>
                    <input type="number" step="0.01" class="form-control" id="wholesale_price" name="wholesale_price" value="<?= old('wholesale_price', $product['wholesale_price']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="stock_quantity" class="form-label">Stock Actual</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?= old('stock_quantity', $product['stock_quantity']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="min_stock" class="form-label">Stock Mínimo</label>
                    <input type="number" class="form-control" id="min_stock" name="min_stock" value="<?= old('min_stock', $product['min_stock']) ?>" required>
                </div>
            </div>

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Actualizar Producto</button>
            <a href="<?= site_url('products') ?>" class="btn btn-secondary btn-lg btn-block">Cancelar</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
