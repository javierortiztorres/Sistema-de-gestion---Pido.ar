<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth Routes
$routes->get('login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Protected Routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Home::index');

    // Clients
    $routes->get('clients/export-csv', 'ClientController::exportCsv');
    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/create', 'ClientController::create');
    $routes->post('clients/store', 'ClientController::store');
    $routes->get('clients/edit/(:num)', 'ClientController::edit/$1');
    $routes->post('clients/update/(:num)', 'ClientController::update/$1');
    $routes->get('clients/delete/(:num)', 'ClientController::delete/$1');

    // Categories
    $routes->get('categories/export-csv', 'CategoryController::exportCsv');
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/create', 'CategoryController::create');
    $routes->post('categories/store', 'CategoryController::store');
    $routes->get('categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');

    // Products
    $routes->get('products/export-csv', 'ProductController::exportCsv');
    $routes->get('products', 'ProductController::index');
    $routes->get('products/create', 'ProductController::create');
    $routes->post('products/store', 'ProductController::store');
    $routes->get('products/edit/(:num)', 'ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->post('products/adjust-stock/(:num)', 'ProductController::adjustStock/$1');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');
    
    // Stock Logs
    $routes->get('stock-logs/export-csv', 'StockLogController::exportCsv');
    $routes->get('stock-logs', 'StockLogController::index');

    // Sales
    $routes->get('sales/export-csv', 'SaleController::exportCsv');
    $routes->get('sales/new', 'SaleController::new');
    $routes->post('sales/store', 'SaleController::store');
    $routes->get('sales', 'SaleController::index');
    $routes->get('sales/details/(:num)', 'SaleController::getDetails/$1');

    // Reports
    $routes->get('reports/invoice/(:num)', 'ReportController::invoice/$1');
    $routes->get('reports/daily-cash', 'ReportController::dailyCash');

    // Utilities
    $routes->get('admin/backup', 'BackupController::index');

    // Users
    $routes->group('users', function($routes) {
        $routes->get('export-csv', 'UserController::exportCsv');
        $routes->get('/', 'UserController::index');
        $routes->get('create', 'UserController::create');
        $routes->post('store', 'UserController::store');
        $routes->get('edit/(:num)', 'UserController::edit/$1');
        $routes->post('update/(:num)', 'UserController::update/$1');
        $routes->get('delete/(:num)', 'UserController::delete/$1');
    });

    // Profile
    $routes->get('auth/profile', 'AuthController::profile');
    $routes->post('auth/update-profile', 'AuthController::updateProfile');

    // Suppliers
    $routes->group('suppliers', function($routes) {
        $routes->get('export-csv', 'SupplierController::exportCsv');
        $routes->get('/', 'SupplierController::index');
        $routes->get('create', 'SupplierController::create');
        $routes->post('store', 'SupplierController::store');
        $routes->get('edit/(:num)', 'SupplierController::edit/$1');
        $routes->post('update/(:num)', 'SupplierController::update/$1');
        $routes->get('delete/(:num)', 'SupplierController::delete/$1');
    });

    // Current Account
    $routes->get('current-account/(:segment)/(:num)', 'CurrentAccountController::view/$1/$2'); // Type/ID
    $routes->post('current-account/payment', 'CurrentAccountController::payment');
});
