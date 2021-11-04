<?php

use App\Controller\CommentController;
use App\Controller\HomeController;
use App\Controller\RegistrationController;
use App\Controller\ResetPasswordController;
use App\Controller\SecurityController;
use App\Controller\TrickController;
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

    $routes->add('app_confirm_account', '/confirmation-compte/{id}/{token}')
        ->controller([RegistrationController::class, 'verifyUserEmail'])
        ->methods(['GET']);

    $routes->add('app_reset_password_request', '/demande-reinitialisation-mot-de-passe')
        ->controller([ResetPasswordController::class, 'request'])
        ->methods(['GET', 'POST']);

    $routes->add('app_reset_password', '/reinitialisation-mot-de-passe/{token}')
        ->controller([ResetPasswordController::class, 'reset'])
        ->methods(['GET', 'POST'])
        ->defaults(['token' => null]);

    $routes->add('app_load_ajax_tricks', '/load-posts/{offset}')
        ->controller([HomeController::class, 'loadMoreTricks'])
        ->methods(['GET']);

    $routes->add('app_trick_new', '/trick/new')
        ->controller([TrickController::class, 'new'])
        ->methods(['GET', 'POST']);

    $routes->add('app_trick_show', '/trick/{slug}')
        ->controller([TrickController::class, 'show'])
        ->methods(['GET']);

    $routes->add('app_comment_new', '/comment/new/{id}')
        ->controller([CommentController::class, 'new'])
        ->methods(['POST']);

    $routes->add('app_load_ajax_comments', '/load-comments/{offset}')
        ->controller([CommentController::class, 'loadMore'])
        ->methods(['GET']);

    $routes->add('app_trick_delete', '/trick/delete/{id}')
        ->controller([TrickController::class, 'delete'])
        ->methods(['GET']);
};
