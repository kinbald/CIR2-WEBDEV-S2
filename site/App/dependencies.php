<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function (Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['twig'];
    $view = new \Slim\Views\Twig('../templates',$settings);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

//Override the default Not Found Handler
//ERREUR 404 not found
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404),'404.twig',['titre'=>'404']);
    };
};