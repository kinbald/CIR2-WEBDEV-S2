<?php
// Routes

$app->get('/b',\App\Controllers\TestController::class)->setName('test');

$app->get('/salut[/{nom}]',\App\Controllers\TestController::class.':salut')->setName("salut");

// setName permet d'appeler path_for('nom_route',{param}) dans twig
$app->get('/index', function (\Slim\Http\Request $request,\Slim\Http\Response  $response, $args) {
    // Render index view
    return $this->view->render($response, 'index.twig', $args);
})->setName("index");

$app->get('/excel[/]',\App\Controllers\TestController::class.':excel');

$app->get('/mail[/]',\App\Controllers\TestController::class.':mail');


/**
 * Routes de base pour la page de connexion
 */
$app->get('/login', 'AuthController:getLogin')->setName("login.get");
$app->post('/login', 'AuthController:postLogin')->setName('login.post');