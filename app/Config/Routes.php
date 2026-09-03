<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Carregar as rotas automáticas do CI4 Shield (/login, /register, /logout)
service('auth')->routes($routes);

// 2. Rota pública inicial (redireciona ou mostra landing page)
$routes->get('/', 'Home::index');

// 3. Grupo de rotas protegidas do Nivora (Requer Login)
$routes->group('', ['filter' => 'session'], static function ($routes) {
    $routes->get('dashboard', static fn () => view('dashboard'));
    
});