<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nuevo Cliente</h1>
</div>

<div class="row">
    <div class="col-md-8 order-md-1">
        <form action="<?= site_url('clients/store') ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Tipo de Cliente</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="retail" <?= old('type') == 'retail' ? 'selected' : '' ?>>Retail (Consumidor Final)</option>
                    <option value="wholesale" <?= old('type') == 'wholesale' ? 'selected' : '' ?>>Mayorista</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-muted">(Opcional)</span></label>
                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Teléfono <span class="text-muted">(Opcional)</span></label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Dirección <span class="text-muted">(Opcional)</span></label>
                <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
            </div>

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar Cliente</button>
            <a href="<?= site_url('clients') ?>" class="btn btn-secondary btn-lg btn-block">Cancelar</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
