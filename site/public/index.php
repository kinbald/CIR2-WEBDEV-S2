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

$app->any('/[salut[/{nom}]]', function (\Slim\Http\Request $request,\Slim\Http\Response $response,$args){
    var_dump($args);
    return $response->getBody()->write('BONJOUR A TOUS'.$args['re']);
});
$app->run();
