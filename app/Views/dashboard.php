<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#customizeDashboardModal">
            <i class="fas fa-cog"></i> Personalizar Dashboard
        </button>
    </div>
</div>

<div class="row" id="dashboard-widgets">
    <!-- Stock Bajo Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-stock-low" data-name="Stock Bajo">
        <div class="card text-white bg-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Stock Bajo</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?= $lowStockCount ?></div>
                        <p class="card-text text-white-50 small">Productos a reponer</p>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-clients" data-name="Clientes (Total)">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Clientes (Total)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalClients ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-products" data-name="Productos">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Productos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalProducts ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Today Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-sales-today" data-name="Ventas (Hoy)">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Ventas (Hoy)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $todaySales ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Sales Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-sales-total" data-name="Ventas (Total)">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Ventas (Total)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalSales ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Receivables Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-receivables" data-name="Total a Cobrar">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total a Cobrar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($totalReceivables, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Payables Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-payables" data-name="Total a Pagar">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total a Pagar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($totalPayables, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Suppliers Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-suppliers" data-name="Proveedores">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Proveedores</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalSuppliers ?></div>
                        <p class="card-text text-muted small">Registrados</p>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Out of Stock Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-out-of-stock" data-name="Sin Stock (Agotados)">
        <div class="card bg-danger text-white shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            Agotados (Sin Stock)</div>
                        <div class="h5 mb-0 font-weight-bold"><?= $outOfStockCount ?></div>
                        <p class="card-text text-white-50 small">Productos indisponibles</p>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-ban fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Sales Today Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-cash-today" data-name="Caja Estimada (Hoy)">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Caja Estimada (Hoy)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($cashSalesToday, 2) ?></div>
                        <p class="card-text text-muted small">Ventas en Efectivo</p>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Max Debtor Card (Widget) -->
    <div class="col-xl-3 col-md-6 mb-4 widget-card" id="widget-max-debtor" data-name="Mayor Deudor">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Mayor Deudor</div>
                        <?php if($maxDebtor): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($maxDebtor['account_balance'], 2) ?></div>
                            <p class="card-text text-muted small text-truncate"><?= esc($maxDebtor['name']) ?></p>
                        <?php else: ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">-</div>
                            <p class="card-text text-muted small">Sin deudores</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= site_url('sales/new') ?>" class="btn btn-success btn-lg">
                        <i class="fas fa-cash-register"></i> Nueva Venta (POS)
                    </a>
                    <a href="<?= site_url('products/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="<?= site_url('clients/create') ?>" class="btn btn-info text-white">
                        <i class="fas fa-user-plus"></i> Nuevo Cliente
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
         <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información</h6>
            </div>
            <div class="card-body">
                <p>Bienvenido al Sistema de Gestión. Utilice el menú superior para navegar.</p>
                <p>Para ver el historial de ventas completo, vaya a la sección de Ventas.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Personalizar Dashboard -->
<div class="modal fade" id="customizeDashboardModal" tabindex="-1" aria-labelledby="customizeDashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customizeDashboardModalLabel">Personalizar Widgets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="widgetSettingsForm">
                    <div class="mb-3">
                        <p class="text-muted">Seleccione los elementos que desea ver en el dashboard:</p>
                        <div id="widgetCheckboxes">
                            <!-- Checkboxes injected via JS -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveWidgetSettings">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const widgetContainer = document.getElementById('dashboard-widgets');
        const widgets = widgetContainer.querySelectorAll('.widget-card');
        const checkboxContainer = document.getElementById('widgetCheckboxes');
        const saveBtn = document.getElementById('saveWidgetSettings');
        
        // Load saved settings
        let savedSettings = localStorage.getItem('dashboard_widgets_prefs');
        let settings = savedSettings ? JSON.parse(savedSettings) : {};

        // If no settings saved (first time), default all to true
        if (!savedSettings) {
            widgets.forEach(w => settings[w.id] = true);
        }

        // Apply settings and build config form
        widgets.forEach(widget => {
            const id = widget.id;
            const name = widget.getAttribute('data-name');
            const isVisible = settings[id] !== false; // Default true if undefined

            // Update visibility
            if (!isVisible) {
                widget.classList.add('d-none');
            } else {
                widget.classList.remove('d-none');
            }

            // Create checkbox for modal
            const div = document.createElement('div');
            div.className = 'form-check';
            div.innerHTML = `
                <input class="form-check-input widget-toggle" type="checkbox" value="${id}" id="check-${id}" ${isVisible ? 'checked' : ''}>
                <label class="form-check-label" for="check-${id}">
                    ${name}
                </label>
            `;
            checkboxContainer.appendChild(div);
        });

        // Save logic
        saveBtn.addEventListener('click', function() {
            const checkboxes = checkboxContainer.querySelectorAll('.widget-toggle');
            checkboxes.forEach(cb => {
                const widgetId = cb.value;
                const isChecked = cb.checked;
                settings[widgetId] = isChecked;

                // Toggle visibility immediately
                const widget = document.getElementById(widgetId);
                if (widget) {
                    if (isChecked) {
                        widget.classList.remove('d-none');
                    } else {
                        widget.classList.add('d-none');
                    }
                }
            });

            // Save to local storage
            localStorage.setItem('dashboard_widgets_prefs', JSON.stringify(settings));

            // Close modal (using Bootstrap 5 JS API if available, or just hide)
            const modalEl = document.getElementById('customizeDashboardModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });
    });
</script>

<?= $this->endSection() ?>
