<?php
// Routes
use App\Controllers\TestController;
$app->group('',function () use ($app)
{
$app->get('/b',\App\Controllers\TestController::class)->setName('test');

$app->get('/salut[/{nom}]',\App\Controllers\TestController::class.':salut')->setName("salut");

// setName permet d'appeler path_for('nom_route',{param}) dans twig
$app->get('/index', function (\Slim\Http\Request $request,\Slim\Http\Response  $response, $args) {
    // Render index view
    return $this->view->render($response, 'index.twig', $args);
})->setName("index");

$app->get('/excel[/]',TestController::class.':excel');

$app->get('/mail[/]',TestController::class.':mail');


})->add(new \App\Middleware\Authentification());


/**
 * Routes de base pour la page de connexion
 */
$app->get('/login', 'AuthController:getLogin')->setName("login.get");
$app->post('/login', 'AuthController:postLogin')->setName('login.post');
$app->get('/logout',App\Controllers\AuthController::class.':logout')->setName("logout");
$app->get('/recover',App\Controllers\AuthController::class.':recover')->setName("recover.get");
$app->post('/recover',App\Controllers\AuthController::class.':sendRecover')->setName("recover.post");
$app->any('/recover/{token}',App\Controllers\AuthController::class.':token')->setName("recoverToken");