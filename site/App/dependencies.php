<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function (Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['twig'];
    $view = new \Slim\Views\Twig($settings['path'],$settings['g']);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

//Override the default Not Found Handler
//ERREUR 404 not found
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404),'error/404.twig');
    };
};

$container['pdo']=function ($c){
    $settings = $c->get('settings')['database'];
    try{
        $db = new  PDO('pgsql:host=' . $settings['host'] . ';dbname=' . $settings['name'] . ';user=' . $settings['user'] . ';password=' . $settings['password']);
        //pour afficher les erreurs
        //uniquemen en dev
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }catch (PDOException $e)
    {
        //uniquement en dev
        echo $e->getMessage();
    }
    return $db;
};