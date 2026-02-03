<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    <style>
        body { padding-top: 56px; }
        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #fff !important;
            background-color: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
    </style>
    <script>
        // Force cleanup of stuck backdrops on load
        document.addEventListener("DOMContentLoaded", function() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = 'auto'; // Restore scrolling
        });
    </script>
</head>
<body>
    <?php $uri = current_url(true); ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <spam class="navbar-brand" href="#"><center>Sistema Gestión</center></spam>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == '' || uri_string() == '/' ? 'active' : '' ?>" href="<?= site_url('/') ?>">
                            <i class="fas fa-home"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'sales/new') === 0 ? 'active' : '' ?>" href="<?= site_url('sales/new') ?>">
                            <i class="fas fa-cash-register"></i>
                            POS (Venta)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'sales' || (strpos(uri_string(), 'sales') === 0 && strpos(uri_string(), 'sales/new') === false) ? 'active' : '' ?>" href="<?= site_url('sales') ?>">
                            <i class="fas fa-list-alt"></i>
                            Historial Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'clients') === 0 ? 'active' : '' ?>" href="<?= site_url('clients') ?>">
                            <i class="fas fa-users"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'suppliers') === 0 ? 'active' : '' ?>" href="<?= site_url('suppliers') ?>">
                            <i class="fas fa-truck"></i>
                            Proveedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'categories') === 0 ? 'active' : '' ?>" href="<?= site_url('categories') ?>">
                            <i class="fas fa-tags"></i>
                            Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'products') === 0 ? 'active' : '' ?>" href="<?= site_url('products') ?>">
                            <i class="fas fa-box"></i>
                            Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'stock-logs') === 0 ? 'active' : '' ?>" href="<?= site_url('stock-logs') ?>">
                            <i class="fas fa-history"></i>
                            Movimientos Stock
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'reports') === 0 ? 'active' : '' ?>" href="<?= site_url('reports/daily-cash') ?>">
                            <i class="fas fa-chart-line"></i>
                            Cierre Caja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'admin/backup') === 0 ? 'active' : '' ?>" href="<?= site_url('admin/backup') ?>">
                            <i class="fas fa-download"></i>
                            Respaldo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'users') === 0 ? 'active' : '' ?>" href="<?= site_url('users') ?>">
                            <i class="fas fa-users-cog"></i>
                            Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'roles') === 0 ? 'active' : '' ?>" href="<?= site_url('roles') ?>">
                            <i class="fas fa-user-shield"></i>
                            Roles
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?= session()->get('name') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= site_url('auth/profile') ?>">Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
    
    <footer class="footer mt-auto py-3 bg-light text-center">
        <div class="container">
            <span class="text-muted">
                &copy; <?= date('Y') ?> Pido.ar - Sistema de Gestión. <br>
                <a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.es" target="_blank">
                    <img alt="Licencia Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" />
                </a><br />
                Esta obra está bajo una <a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.es" target="_blank">Licencia Creative Commons Atribución-NoComercial-CompartirIgual 4.0 Internacional</a>.
            </span>
        </div>
    </footer>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
