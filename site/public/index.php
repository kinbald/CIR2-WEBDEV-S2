<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 14/03/17
 * Time: 23:03
 */

ini_set('display_errors', 'On');
require '../vendor/autoload.php';
$settings = require __DIR__ . '/../App/settings.php';
$app = new \Slim\App($settings);

// Fetch DI Container
$container = $app->getContainer();
require __DIR__.'/../App/routes.php';
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
//load routes
require __DIR__.'/../App/routes.php';
//run app
$app->run();

