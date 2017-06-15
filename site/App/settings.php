<?php
include __DIR__.'/private_settings.php';

/**
 * Taille des mots de passe à générer
 */
define("PASSWORD_SIZE", 8);

/**
 * Base pour la génération des mots de passe
 */
define("SEED", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        //TWIG settings
        'twig'=>[
            'path'=>[
                '../templates/','../templates/pages'
                ],
            'g'=>[
                'cache'=>false,
            ],
        ],
        //DATABASE settings
        'database'=>[
            'name'=>'bdd',
            'user'=>'guillaume',
            'password'=>'root',
            'host'=>'db_postgres',
        ],
        //DEBUG
        'debug'=>1,
        //MAILER settings
        'smtp'=>[
            'host'=>'smtp.gmail.com',
            'port'=>'465',
            'username'=>'testleasen@gmail.com',
            'password'=>$password_mailer,
        ],
    ],
];