<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cuenta Corriente: <?= esc($entity['name']) ?></h1>
    <a href="<?= site_url($type == 'client' ? 'clients' : 'suppliers') ?>" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-light mb-3">
            <div class="card-header">Saldo Actual</div>
            <div class="card-body">
                <h3 class="card-title text-center <?= $entity['account_balance'] > 0 ? ($type == 'client' ? 'text-danger' : 'text-warning') : 'text-success' ?>">
                    $<?= number_format($entity['account_balance'], 2) ?>
                </h3>
                <p class="card-text text-center text-muted">
                    <?= $type == 'client' ? 'Deuda del Cliente' : 'Deuda con el Proveedor' ?>
                </p>
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    Registrar Movimiento
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <h4>Movimientos</h4>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th class="text-end">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movements as $mov): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($mov['created_at'])) ?></td>
                            <td>
                                <?= esc($mov['description']) ?>
                                <?php if($mov['reference_id']): ?>
                                    <small class="text-muted">(Ref: #<?= $mov['reference_id'] ?>)</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($mov['type'] == 'debit'): ?>
                                    <span class="badge bg-danger">Débito</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Crédito</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                                $<?= number_format($mov['amount'], 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= site_url('current-account/payment') ?>" method="post">
        <input type="hidden" name="type" value="<?= $type ?>">
        <input type="hidden" name="entity_id" value="<?= $entity['id'] ?>">
        
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar Movimiento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label for="mov_type" class="form-label">Tipo de Movimiento</label>
                <select class="form-select" name="mov_type" id="mov_type">
                    <option value="payment">Pago (Reduce Deuda / Haber)</option>
                    <option value="debt">Cargo / Nota de Débito (Aumenta Deuda / Debe)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Monto</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción / Nota</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Ej: Pago parcial, Transferencia...">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Registrar</button>
          </div>
        </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
