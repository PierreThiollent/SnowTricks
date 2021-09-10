<?php

use App\Controller\HomeController;
use App\Controller\SecurityController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes->add('app_home', '/')
        ->controller([HomeController::class, 'index'])
        ->methods(['GET']);

    $routes->add('app_login', '/connexion')
        ->controller([SecurityController::class, 'login'])
        ->methods(['GET', 'POST']);

    $routes->add('app_logout', '/deconnexion')
        ->controller([SecurityController::class, 'logout'])
        ->methods(['GET']);
};
