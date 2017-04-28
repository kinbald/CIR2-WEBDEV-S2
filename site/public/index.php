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

// Set up dependencies
require __DIR__ . '/../App/dependencies.php';

//load routes
require __DIR__.'/../App/routes.php';
//run app
$app->run();

