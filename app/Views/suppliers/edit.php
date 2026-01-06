<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Proveedor</h1>
    <a href="<?= site_url('suppliers') ?>" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= site_url('suppliers/update/' . $supplier['id']) ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre / Razón Social</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $supplier['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $supplier['email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone', $supplier['phone']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
