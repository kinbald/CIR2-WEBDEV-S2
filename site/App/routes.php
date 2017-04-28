<?php
// Routes

$app->get('/b',\App\Controllers\TestController::class)->setName('test');

$app->get('/salut[/{nom}]', function (\Slim\Http\Request $request,\Slim\Http\Response $response,$args){
    $args['titre']='Un titre';
    return $this->view->render($response,'test.twig', $args);
})->setName("salut");

// setName permet d'appeler path_for('nom_route',{param}) dans twig
$app->get('/index', function (\Slim\Http\Request $request,\Slim\Http\Response  $response, $args) {
    // Render index view
    return $this->view->render($response, 'index.twig', $args);
})->setName("index");