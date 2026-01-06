<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mi Perfil</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= site_url('auth/update-profile') ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name', session()->get('name')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email (No se puede cambiar)</label>
                        <input type="email" class="form-control" value="<?= session()->get('email') ?>" disabled>
                    </div>

                    <hr>

                    <h5>Cambiar Contraseña</h5>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Contraseña Actual (Requerida para cambios)</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña (Opcional)</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6">
                    </div>
                    
                    <div class="mb-3">
                         <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                         <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
