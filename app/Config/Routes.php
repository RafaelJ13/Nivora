<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Carregar as rotas automáticas do CI4 Shield (/login, /register, /logout)
service('auth')->routes($routes);

// 2. Rota pública inicial (redireciona ou mostra landing page)
$routes->get('/', 'Home::index');
$routes->get('privacidade', static fn() => view('legal/privacy'));
$routes->get('termos', static fn() => view('legal/terms'));
$routes->get('cookies', static fn() => view('legal/cookies'));
$routes->get('contacto', static fn() => view('legal/contact'));

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


    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/new', 'CategoryController::new');
    $routes->get('categories/(:num)/edit', 'CategoryController::edit/$1');
    $routes->post('categories', 'CategoryController::post');
    $routes->put('categories/(:num)', 'CategoryController::put/$1');
    $routes->delete('categories/(:num)', 'CategoryController::delete/$1');

    $routes->get('transactions', 'TransactionController::index');
    $routes->get('transactions/new', 'TransactionController::new');
    $routes->get('transactions/(:num)/edit', 'TransactionController::edit/$1');
    $routes->get('transactions/(:num)', 'TransactionController::show/$1');
    $routes->post('transactions', 'TransactionController::post');
    $routes->put('transactions/(:num)', 'TransactionController::put/$1');
    $routes->delete('transactions/(:num)', 'TransactionController::delete/$1');
});