<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 14/03/17
 * Time: 23:03
 */

ini_set('display_errors', 'On');
require '../vendor/autoload.php';
$app = new Slim\App([
    'settings' =>[
        'displayErrorDetails' => true
    ]
]);

// Fetch DI Container
$container = $app->getContainer();


// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('../templates', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$app->get('/[salut[/{nom}]]', function (\Slim\Http\Request $request,\Slim\Http\Response $response,$args){
    return $this->view->render($response,'test.twig', [
        'nom' => $args['nom'],
        'titre'=>'Un titre'
    ]);
});
$app->run();

