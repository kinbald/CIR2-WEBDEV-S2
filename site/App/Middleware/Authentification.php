<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 16/05/17
 * Time: 21:54
 */

namespace App\Middleware;

use App\Models\Token_Admin;
use App\Models\Token_responsable_legal;

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
        if($_SESSION["admin"]>0)
        {
            //verification
        }else if($_SESSION["RL"]>0)
        {
            //verifie co utilisateur lambda
        }else
        {
            //verifie si les cookies permettent une connection automatique
            $rl=(new Token_responsable_legal())->verifyRememberMe();
            $admin=(new Token_Admin())->verifyRememberMe();
            if($rl)
            {
                $_SESSION["RL"]=$rl;
            }else if($admin){
                $_SESSION["admin"]=$admin;
            }else
            {
                return $response->withHeader('Location','/login');
            }
        }
        $response = $next($request, $response);
        //après
        return $response;
    }
}