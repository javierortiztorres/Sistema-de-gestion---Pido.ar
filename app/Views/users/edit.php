<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Usuario</h1>
    <a href="<?= site_url('users') ?>" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= site_url('users/update/' . $user['id']) ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-select" id="role" name="role">
                            <option value="employee" <?= old('role', $user['role']) == 'employee' ? 'selected' : '' ?>>Empleado</option>
                            <option value="admin" <?= old('role', $user['role']) == 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña (Dejar en blanco para mantener)</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6">
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
