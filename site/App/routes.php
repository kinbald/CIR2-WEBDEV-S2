<?php
// Routes
use App\Controllers\TestController;

$app->group('', function () use ($app) {
    $app->get('/b', \App\Controllers\TestController::class)->setName('test');

    $app->get('/salut[/{nom}]', \App\Controllers\TestController::class . ':salut')->setName("salut");

// setName permet d'appeler path_for('nom_route',{param}) dans twig
    $app->get('/index', \App\Controllers\UserController::class . ':getIndex')->setName("index");

    $app->get('/excel[/]', TestController::class . ':excel');

    $app->get('/mail[/]', TestController::class . ':mail');


})->add(new \App\Middleware\Authentification($container));

/**
 * Routes de base pour la page de connexion
 */
$app->group('', function () use ($app) {
    $app->get('/login', 'AuthController:getLogin')->setName('login.get');
    $app->post('/login', 'AuthController:postLogin')->setName('login.post');
})->add(new \App\Middleware\ValidationErreursMiddleware($container))
    ->add(new \App\Middleware\PersitenceFormulaireMiddleware($container));

$app->get('/logout', App\Controllers\AuthController::class . ':logout')->setName("logout");
$app->get('/recover', App\Controllers\AuthController::class . ':recover')->setName("recover.get");
$app->post('/recover', App\Controllers\AuthController::class . ':sendRecover')->setName("recover.post");
$app->get('/recover/{token}', App\Controllers\AuthController::class . ':token')->setName("recoverToken.get");
$app->post('/recover/{token}', App\Controllers\AuthController::class . ':tokenValidation')->setName("recoverToken.post");

//MW authentification appliqué avant le middleware verification rl
$app->group('/calendrier/', function () use ($app) {
    $app->get('{id_enfant}',App\Controllers\CreneauController::class.':calendrier')->setName("calendrier");
    $app->post('ajax/getEvents/{id_enfant}', \App\Controllers\CreneauController::class . ':getMoisEnfant')->setName('AJAX-getMoisEnfant');
    $app->post('ajax/SetDay/{id_enfant}', \App\Controllers\CreneauController::class . ':modifieCreneau')->setName('AJAX-modifieCreneau');
})->add(new App\Middleware\VerificationRl($container))->add(new \App\Middleware\Authentification($container));

$app->get('/getActivite',\App\Controllers\CreneauController::class.'getActivite');