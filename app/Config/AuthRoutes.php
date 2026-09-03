<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthRoutes as ShieldAuthRoutes;

class AuthRoutes extends ShieldAuthRoutes
{
    public function __construct()
    {
        parent::__construct();

        $this->routes['register'] = [
            [
                'get',
                'register',
                '\\App\\Controllers\\RegisterController::registerView',
                'register',
            ],
            [
                'post',
                'register',
                '\\App\\Controllers\\RegisterController::registerAction',
            ],
        ];
    }
}
