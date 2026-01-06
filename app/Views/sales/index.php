<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Historial de Ventas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= site_url('sales/new') ?>" class="btn btn-sm btn-outline-success">Nueva Venta</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($sales) && is_array($sales)): ?>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= esc($sale['id']) ?></td>
                    <td><?= esc(date('d/m/Y H:i', strtotime($sale['created_at']))) ?></td>
                    <td>
                        <?= !empty($sale['client_name']) ? esc($sale['client_name']) : '<span class="text-muted">Consumidor Final</span>' ?>
                    </td>
                    <td><?= ucfirst($sale['type']) ?></td>
                    <td>$<?= number_format($sale['total'], 2) ?></td>
                    <td>
                        <a href="<?= site_url('reports/invoice/' . $sale['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            📄 Imprimir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No hay ventas registradas.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
    
    <div class="d-flex justify-content-center">
        <!-- Pagination Links could go here -->
    </div>
</div>
<?= $this->endSection() ?>
