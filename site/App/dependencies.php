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
$container['notFoundHandler'] = function (Psr\Container\ContainerInterface $c) {
    return function (\Slim\Http\Request $request,\Slim\Http\Response $response) use ($c) {
        return $c['view']->render($response->withStatus(404),'error/404.twig');
    };
};

//mise en place de la connection avec la base de donnée
$container['pdo']=function (Psr\Container\ContainerInterface $c){
    $settings = $c->get('settings')['database'];
    try{
        $db = new  PDO('pgsql:host=' . $settings['host'] . ';dbname=' . $settings['name'] . ';user=' . $settings['user'] . ';password=' . $settings['password']);
        //pour afficher les erreurs
        //uniquemen en dev
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }catch (PDOException $e)
    {
        //uniquement en dev
        if($c->get('settings')['debug']==1)
        {
            echo $e->getMessage();
        }

    }
    return $db;
};

//mise en place du mailer
$container['mailer']=function (Psr\Container\ContainerInterface $c){
    $settings=$c->get('settings')['smtp'];
    $transport = Swift_SmtpTransport::newInstance($settings['host'], $settings['port'])
        ->setUsername($settings['username'])
        ->setPassword($settings['password'])
        ->setEncryption('ssl')
    ;
    return  Swift_Mailer::newInstance($transport);
};

// Gestion du contrôleur d'authentification
$container['AuthController'] = function ($container) {
    return new App\Controllers\AuthController($container);
};