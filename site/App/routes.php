<?php
// Routes
use App\Controllers\TestController;
$app->group('',function () use ($app)
{
$app->get('/b',\App\Controllers\TestController::class)->setName('test');

$app->get('/salut[/{nom}]',\App\Controllers\TestController::class.':salut')->setName("salut");

// setName permet d'appeler path_for('nom_route',{param}) dans twig
$app->get('/index', \App\Controllers\UserController::class.':getIndex')->setName("index");

$app->get('/contact', \App\Controllers\ContactController::class.':getContact')->setName("contact.get");
$app->post('/contact', \App\Controllers\ContactController::class.':postContact')->setName("contact.post");

$app->get('/excel[/]',TestController::class.':excel');

$app->get('/mail[/]',TestController::class.':mail');


})->add(new \App\Middleware\Authentification($container));

/**
 * Routes de base pour la page de connexion
 */
$app->group('', function () use ($app)
{
    $app->get('/login', 'AuthController:getLogin')->setName('login.get');
    $app->post('/login', 'AuthController:postLogin')->setName('login.post');
    $app->get('/login-admin', App\Controllers\AuthAdminController::class.':getLoginAd')->setName('login-admin.get');
    $app->post('/login-admin', App\Controllers\AuthAdminController::class.':postLoginAd')->setName('login-admin.post');
})->add(new \App\Middleware\ValidationErreursMiddleware($container))
    ->add(new \App\Middleware\PersitenceFormulaireMiddleware($container));

$app->get('/exportData',\App\Controllers\ExportDataController::class.':getExportData')->setName("exportData.get");
$app->post('/exportData',\App\Controllers\ExportDataController::class.':postExportData')->setName("exportData.post");
$app->get('/importData',\App\Controllers\ImportDataController::class.':getImportData')->setName("importData.get");
$app->post('/importData',\App\Controllers\ImportDataController::class.':postImportData')->setName("importData.post");
$app->get('/logout-admin',App\Controllers\AuthAdminController::class.':logoutAd')->setName("logout-admin");
$app->get('/recover-admin',App\Controllers\AuthAdminController::class.':recoverAd')->setName("recover-admin.get");
$app->post('/recover-admin',App\Controllers\AuthAdminController::class.':sendRecoverAd')->setName("recover-admin.post");
$app->get('/recover-admin/{token}',App\Controllers\AuthAdminController::class.':tokenAd')->setName("recoverToken-admin.get");
$app->post('/recover-admin/{token}',App\Controllers\AuthAdminController::class.':tokenValidationAd')->setName("recoverToken-admin.post");

$app->get('/logout',App\Controllers\AuthController::class.':logout')->setName("logout");
$app->get('/recover',App\Controllers\AuthController::class.':recover')->setName("recover.get");
$app->post('/recover',App\Controllers\AuthController::class.':sendRecover')->setName("recover.post");
$app->get('/recover/{token}',App\Controllers\AuthController::class.':token')->setName("recoverToken.get");
$app->post('/recover/{token}',App\Controllers\AuthController::class.':tokenValidation')->setName("recoverToken.post");