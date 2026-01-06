<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nueva Categoría</h1>
</div>

<div class="row">
    <div class="col-md-8 order-md-1">
        <form action="<?= site_url('categories/store') ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de Categoría</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
            </div>

            <div class="mb-3">
                <label for="parent_id" class="form-label">Categoría Padre <span class="text-muted">(Opcional)</span></label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="">Ninguna (Categoría Principal)</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= old('parent_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción <span class="text-muted">(Opcional)</span></label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
            </div>

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar Categoría</button>
            <a href="<?= site_url('categories') ?>" class="btn btn-secondary btn-lg btn-block">Cancelar</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
