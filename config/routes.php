<?php

use App\Controller\HomeController;
use App\Controller\RegistrationController;
use App\Controller\ResetPasswordController;
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

    $routes->add('app_register', '/inscription')
        ->controller([RegistrationController::class, 'register'])
        ->methods(['GET', 'POST']);

    $routes->add('app_confirm_account', '/confirmation-compte')
        ->controller([RegistrationController::class, 'verifyUserEmail'])
        ->methods(['GET']);

    $routes->add('app_reset_password_request', '/demande-reinitialisation-mot-de-passe')
        ->controller([ResetPasswordController::class, 'request'])
        ->methods(['GET', 'POST']);

    $routes->add('app_reset_password_check_email', '/reinitialisation-mot-de-passe/verif-email')
        ->controller([ResetPasswordController::class, 'checkEmail'])
        ->methods(['GET', 'POST']);

    $routes->add('app_reset_password', '/reinitialisation-mot-de-passe/{token}')
        ->controller([ResetPasswordController::class, 'reset'])
        ->methods(['GET', 'POST'])
        ->defaults(['token' => null]);

    $routes->add('app_load_ajax_tricks', '/load-posts/{offset}')
        ->controller([HomeController::class, 'loadMoreTricks'])
        ->methods(['GET']);
};
