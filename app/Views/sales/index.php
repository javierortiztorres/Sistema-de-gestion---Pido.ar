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
                <th>Método</th>
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
                    <td>
                        <?php 
                            $methods = [
                                'cash' => 'Efectivo',
                                'credit_card' => 'Crédito',
                                'debit_card' => 'Débito',
                                'transfer' => 'Transf.'
                            ];
                            echo $methods[$sale['payment_method']] ?? $sale['payment_method'];
                        ?>
                    </td>
                    <td>$<?= number_format($sale['total'], 2) ?></td>
                    <td>
                        <button class="btn btn-sm btn-info view-details" data-id="<?= $sale['id'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="<?= site_url('reports/invoice/' . $sale['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No hay ventas registradas.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de Venta #<span id="modal-sale-id"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
             <div class="col-6"><strong>Fecha:</strong> <span id="modal-date"></span></div>
             <div class="col-6 text-end"><strong>Método:</strong> <span id="modal-method"></span></div>
        </div>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Código</th>
                    <th class="text-end">Cant.</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Desc.</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody id="modal-items">
                <!-- Items via JS -->
            </tbody>
            <tfoot>
                 <tr>
                     <td colspan="5" class="text-end"><strong>Total Venta:</strong></td>
                     <td class="text-end fs-5 fw-bold" id="modal-total"></td>
                 </tr>
            </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('detailsModal');
        const modal = new bootstrap.Modal(modalEl);
        
        document.querySelectorAll('.view-details').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                
                // Clear previous
                document.getElementById('modal-items').innerHTML = '<tr><td colspan="6" class="text-center">Cargando...</td></tr>';
                modal.show();

                try {
                    const response = await fetch(`<?= site_url('sales/details/') ?>/${id}`);
                    const data = await response.json();

                    if(data.status === 'success') {
                        document.getElementById('modal-sale-id').textContent = data.sale.id;
                        document.getElementById('modal-date').textContent = new Date(data.sale.created_at).toLocaleString();
                        document.getElementById('modal-method').textContent = data.sale.payment_method;
                        document.getElementById('modal-total').textContent = '$' + parseFloat(data.sale.total).toFixed(2);

                        const tbody = document.getElementById('modal-items');
                        tbody.innerHTML = '';

                        data.items.forEach(item => {
                            const subtotal = (item.price * item.quantity) - item.discount;
                            const row = `
                                <tr>
                                    <td>${item.product_name}</td>
                                    <td>${item.product_code}</td>
                                    <td class="text-end">${item.quantity}</td>
                                    <td class="text-end">$${parseFloat(item.price).toFixed(2)}</td>
                                    <td class="text-end">$${parseFloat(item.discount).toFixed(2)}</td>
                                    <td class="text-end">$${subtotal.toFixed(2)}</td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    } else {
                        alert('Error al cargar detalles');
                    }
                } catch (e) {
                    console.error(e);
                    alert('Error de conexión');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
