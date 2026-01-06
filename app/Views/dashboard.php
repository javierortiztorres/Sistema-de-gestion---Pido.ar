<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Stock Bajo</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $lowStockCount ?></h5>
                    <p class="card-text">Productos requieren reposición.</p>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <!-- Clients Card -->
    <div class="col-xl-3 col-md-6 mb-4">
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

    <!-- Products Card -->
    <div class="col-xl-3 col-md-6 mb-4">
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

    <!-- Sales Today Card -->
    <div class="col-xl-3 col-md-6 mb-4">
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

    <!-- Total Sales Card -->
    <div class="col-xl-3 col-md-6 mb-4">
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
                <p>Para ver el historial de ventas completo, vaya a la sección de Ventas (Próximamente).</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
