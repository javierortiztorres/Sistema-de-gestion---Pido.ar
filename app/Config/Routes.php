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
    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/create', 'ClientController::create');
    $routes->post('clients/store', 'ClientController::store');
    $routes->get('clients/edit/(:num)', 'ClientController::edit/$1');
    $routes->post('clients/update/(:num)', 'ClientController::update/$1');
    $routes->get('clients/delete/(:num)', 'ClientController::delete/$1');

    // Categories
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/create', 'CategoryController::create');
    $routes->post('categories/store', 'CategoryController::store');
    $routes->get('categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');

    // Products
    $routes->get('products', 'ProductController::index');
    $routes->get('products/create', 'ProductController::create');
    $routes->post('products/store', 'ProductController::store');
    $routes->get('products/edit/(:num)', 'ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->post('products/adjust-stock/(:num)', 'ProductController::adjustStock/$1');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');

    // Sales
    $routes->get('sales/new', 'SaleController::new');
    $routes->post('sales/store', 'SaleController::store');
    $routes->get('sales', 'SaleController::index');

    // Reports
    $routes->get('reports/invoice/(:num)', 'ReportController::invoice/$1');
});


