<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Clients
$routes->get('clients', 'ClientController::index');
$routes->get('clients/create', 'ClientController::create');
$routes->post('clients/store', 'ClientController::store');
$routes->get('clients/edit/(:num)', 'ClientController::edit/$1');
$routes->post('clients/update/(:num)', 'ClientController::update/$1');
$routes->get('clients/delete/(:num)', 'ClientController::delete/$1');

// Products
$routes->get('products', 'ProductController::index');
$routes->get('products/create', 'ProductController::create');
$routes->post('products/store', 'ProductController::store');
$routes->get('products/edit/(:num)', 'ProductController::edit/$1');
$routes->post('products/update/(:num)', 'ProductController::update/$1');
$routes->get('products/delete/(:num)', 'ProductController::delete/$1');

// Sales
$routes->get('sales/new', 'SaleController::new');
$routes->post('sales/store', 'SaleController::store');

