<?php
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
        'database'=>[
            'name'=>'bdd',
            'user'=>'william',
            'password'=>'root',
            'host'=>'db_postgres',
        ]
    ],
];