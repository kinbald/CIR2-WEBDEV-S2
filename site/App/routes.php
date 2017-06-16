<?php
// Routes

$app->group('', function () use ($app) {

// setName permet d'appeler path_for('nom_route',{param}) dans twig
    $app->get('/index', \App\Controllers\UserController::class . ':getIndex')->setName("index");
    $app->get('/', \App\Controllers\UserController::class . ':getIndex')->setName("index");
    $app->get('/logout', App\Controllers\AuthController::class . ':logout')->setName("logout");
})->add(new \App\Middleware\Authentification($container));

/**
 * Routes de base pour la page de connexion
 */
$app->group('', function () use ($app) {
    $app->get('/login', 'AuthController:getLogin')->setName('login.get');
    $app->post('/login', 'AuthController:postLogin')->setName('login.post');
    $app->get('/login-admin', App\Controllers\AuthAdminController::class . ':getLoginAd')->setName('login-admin.get');
    $app->post('/login-admin', App\Controllers\AuthAdminController::class . ':postLoginAd')->setName('login-admin.post');
})->add(new \App\Middleware\ValidationErreursMiddleware($container))
    ->add(new \App\Middleware\PersitenceFormulaireMiddleware($container))->add(new App\Middleware\NonAuthentifieMiddleware($container));


//route de gestion du compte
$app->get('/recover', App\Controllers\AuthController::class . ':recover')->setName("recover.get");
$app->post('/recover', App\Controllers\AuthController::class . ':sendRecover')->setName("recover.post");
$app->get('/recover/{token}', App\Controllers\AuthController::class . ':token')->setName("recoverToken.get");
$app->post('/recover/{token}', App\Controllers\AuthController::class . ':tokenValidation')->setName("recoverToken.post");


/**
 * route du calendrier
 */
$app->group('/calendrier/', function () use ($app) {
    $app->get('{id_enfant}', App\Controllers\CreneauController::class . ':calendrier')->setName("calendrier");
    $app->post('ajax/getEvents/{id_enfant}', \App\Controllers\CreneauController::class . ':getMoisEnfant')->setName('AJAX-getMoisEnfant');
    $app->post('ajax/SetDay/{id_enfant}', \App\Controllers\CreneauController::class . ':modifieCreneau')->setName('AJAX-modifieCreneau');
})->add(new App\Middleware\VerificationRl($container))->add(new \App\Middleware\Authentification($container));

//route permettant de recuperer les activité d'un enfants, à ajouter au dessus?
$app->post('/getActivite', \App\Controllers\CreneauController::class . ':getActivite');
$app->get('/recover-admin', App\Controllers\AuthAdminController::class . ':recoverAd')->setName("recover-admin.get");
$app->post('/recover-admin', App\Controllers\AuthAdminController::class . ':sendRecoverAd')->setName("recover-admin.post");
$app->get('/recover-admin/{token}', App\Controllers\AuthAdminController::class . ':tokenAd')->setName("recoverToken-admin.get");
$app->post('/recover-admin/{token}', App\Controllers\AuthAdminController::class . ':tokenValidationAd')->setName("recoverToken-admin.post");

$app->group('/admin/', function () use ($app) {
    $app->get('regenerer', \App\Controllers\AdminController::class . ':getAdminRegenerer')->setName("regenerer");
    $app->post('regenerer', \App\Controllers\AdminController::class . ':regenererCompte');

    $app->get('utilisateur-enfant', \App\Controllers\AdminController::class . ':utilisateurEnfant')->setName("utilisateur-enfant");
    $app->post('utilisateur-enfant', \App\Controllers\AdminController::class . ':associe_RL_Enfant')->setName("utilisateur-enfant.post");
    
    $app->get('chercherEnfant', \App\Controllers\AdminController::class.':chercherEnfant')->setName('chercherEnfant');

    $app->get('rl/{id_responsable_legal}', \App\Controllers\AdminController::class . ':getModifierRL')->setName("getModifierRL");
    $app->post('rl/{id_responsable_legal}', \App\Controllers\AdminController::class . ':postModifierRL');

    $app->post('ajax/getUser/', \App\Controllers\AdminController::class . ':getUserByName');
    $app->post('ajax/getChild/', \App\Controllers\AdminController::class . ':getChildByName');

    $app->get('index', \App\Controllers\UserController::class . ':getIndexAd')->setName("index-admin");

    $app->post('imprimerPassword', \App\Controllers\AdminController::class . ':getPasswordImpression')->setName("admin.regenerer");
    $app->get('logout', App\Controllers\AuthAdminController::class . ':logoutAd')->setName("logout-admin");

    $app->get('importData', \App\Controllers\ImportDataController::class . ':getImportData')->setName("importData.get");
    $app->post('importData', \App\Controllers\ImportDataController::class . ':postImportData')->setName("importData.post");

    $app->get('classe', \App\Controllers\ClasseController::class . ':getClasse')->setName("classe-admin.get");
    $app->post('classe', \App\Controllers\ClasseController::class . ':postClasse')->setName("classe-admin.post");

    $app->get('enfant', \App\Controllers\EnfantController::class . ':getEnfant')->setName("enfant-admin.get");
    $app->post('enfant', \App\Controllers\EnfantController::class . ':postEnfant')->setName("enfant-admin.post");
    
    $app->get('activite', \App\Controllers\ActiviteController::class . ':getActivite')->setName("activite-admin.get");
    $app->post('activite', \App\Controllers\ActiviteController::class . ':postActivite')->setName("activite-admin.post");


    $app->post('index', App\Controllers\AuthAdminController::class . ':insertRespoLegal')->setName('add-user.post');
})->add(new \App\Middleware\AdminAuthentification($container))->add(new \App\Middleware\FlashMessagesMiddleware($container));


//pages de contact
$app->group('/contact', function () use ($app) {

    $app->get('', \App\Controllers\ContactController::class . ':getContact')->setName("contact.get");
    $app->post('', \App\Controllers\ContactController::class . ':postContact')->setName("contact.post");


})->add(new \App\Middleware\ValidationErreursMiddleware($container))
    ->add(new \App\Middleware\PersitenceFormulaireMiddleware($container));