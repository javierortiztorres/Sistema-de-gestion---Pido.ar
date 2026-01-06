<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cierre de Caja del Día (<?= date('d/m/Y', strtotime($today)) ?>)</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">Imprimir Reporte</button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Total Card -->
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Recaudado</div>
            <div class="card-body">
                <h2 class="card-title">$<?= number_format($totalSales, 2) ?></h2>
                <p class="card-text"><?= $salesCount ?> Ventas realizadas hoy</p>
            </div>
        </div>
    </div>
    
    <!-- Methods Summary -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">Desglose por Método de Pago</div>
            <div class="card-body">
                <div class="row">
                    <?php if(empty($byMethod)): ?>
                        <div class="col-12"><p class="text-muted">No hay movimientos registrados.</p></div>
                    <?php else: ?>
                        <?php foreach($byMethod as $method): ?>
                            <div class="col-md-6 mb-3">
                                <h5 class="text-capitalize">
                                    <?php 
                                        $labels = [
                                            'cash' => 'Efectivo', 
                                            'credit_card' => 'Crédito', 
                                            'debit_card' => 'Débito', 
                                            'transfer' => 'Transferencia'
                                        ];
                                        echo $labels[$method['payment_method']] ?? $method['payment_method'];
                                    ?>
                                </h5>
                                <h4 class="text-primary">$<?= number_format($method['method_total'], 2) ?></h4>
                                <small class="text-muted"><?= $method['method_count'] ?> transacciones</small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<h3>Detalle de Transacciones</h3>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Método</th>
                <th>Desc.</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sales as $sale): ?>
            <tr>
                <td><?= date('H:i', strtotime($sale['created_at'])) ?></td>
                <td><?= $sale['client_name'] ? esc($sale['client_name']) : 'Consumidor Final' ?></td>
                <td>
                    <span class="badge bg-<?= $sale['type'] == 'wholesale' ? 'info' : 'secondary' ?>">
                        <?= $sale['type'] == 'wholesale' ? 'Mayorista' : 'Minorista' ?>
                    </span>
                </td>
                <td class="text-capitalize">
                     <?php 
                        echo $labels[$sale['payment_method']] ?? $sale['payment_method'];
                    ?>
                </td>
                <td>$<?= number_format($sale['discount'], 2) ?></td>
                <td><strong>$<?= number_format($sale['total'], 2) ?></strong></td>
                <td>
                    <a href="<?= site_url('reports/invoice/' . $sale['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
