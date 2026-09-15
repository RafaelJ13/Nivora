<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Carregar as rotas automáticas do CI4 Shield (/login, /register, /logout)
service('auth')->routes($routes);

// 2. Rota pública inicial (redireciona ou mostra landing page)
$routes->get('/', 'Home::index');

// Rotas apenas acessiveis apos login
$routes->group('', ['filter' => 'session' ], static function($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('accounts', 'AccountsController::index');
    $routes->get('accounts/create', 'AccountsController::create');
    $routes->get('accounts/(:num)/edit', 'AccountsController::edit/$1');
    $routes->get('accounts/(:num)', 'AccountsController::show/$1');
    $routes->post('accounts', 'AccountsController::post');
    $routes->delete('accounts/(:num)', 'AccountsController::delete/$1');
    $routes->put('accounts/(:num)', 'AccountsController::put/$1');
});