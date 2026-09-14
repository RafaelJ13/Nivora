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
    $routes->get('dashboard', static fn () => view('dashboard'));
    $routes->get('accounts', 'AccountsController::index');
    $routes->get('accounts/create', 'AccountsController::create');
    $routes->post('accounts', 'AccountsController::post');
});