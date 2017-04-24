<?php
// Routes

$app->get('/salut[/{nom}]', function (\Slim\Http\Request $request,\Slim\Http\Response $response,$args){
    return $this->view->render($response,'test.twig', [
        'nom' => $args['nom'],
        'titre'=>'Un titre'
    ]);
});

$app->get('/b', function (\Slim\Http\Request $request,\Slim\Http\Response  $response, $args) {
    // Render index view
    return $this->view->render($response, 'layout.twig', $args);
});

$app->get('/index', function (\Slim\Http\Request $request,\Slim\Http\Response  $response, $args) {
    // Render index view
    return $this->view->render($response, 'index.twig', $args);
});