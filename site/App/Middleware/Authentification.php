<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 16/05/17
 * Time: 21:54
 */

namespace App\Middleware;


use App\Models\Admin;

class Authentification
{

    public function __invoke($request, $response, $next)
    {
        //verification connection
        //avant le traitement de la route/prochain middleware
        //passage de donnée :
        //verifier type co : admin/normal
        //pour transmettre valeurs :
        //$request->withAttribute('cle','valeur');
        if($_SESSION["admin"]==true)
        {
            //verification


        }else if($_SESSION["RL"]==true)
        {
            //verifie co utilisateur lambda
        }
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');
        //après
        return $response;
    }
}