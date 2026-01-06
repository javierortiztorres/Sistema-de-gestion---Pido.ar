<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Historial de Stock</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Cambio</th>
                        <th>Razón</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay registros de movimientos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                <td><?= esc($log['product_name']) ?></td>
                                <td><span class="badge bg-secondary"><?= esc($log['product_code']) ?></span></td>
                                <td>
                                    <?php if ($log['change_amount'] > 0): ?>
                                        <span class="text-success fw-bold">+<?= $log['change_amount'] ?></span>
                                    <?php elseif ($log['change_amount'] < 0): ?>
                                        <span class="text-danger fw-bold"><?= $log['change_amount'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log['reason']) ?></td>
                                <td><?= esc($log['user_name']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
