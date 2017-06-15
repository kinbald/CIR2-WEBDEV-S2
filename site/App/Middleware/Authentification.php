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
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;
use Slim\Router;

/**
 * @property Router router
 */
class Authentification extends Middleware
{

    public function __invoke(Request $request, Response $response,  callable $next)
    {
        //verification connection
        //avant le traitement de la route/prochain middleware
        //passage de donnée :
        //verifier type co : admin/normal
        //pour transmettre valeurs :
        //$request->withAttribute('cle','valeur');
        if($this->sessionInstance->read("admin") > 0)
        {
            //verification
        }
        else if($this->sessionInstance->read("RL") >0)
        {
            //verifie co utilisateur lambda
        }
        else
        {
            //verifie si les cookies permettent une connection automatique
            $rl=(new Token_responsable_legal())->verifyRememberMe();
            $admin=(new Token_Admin())->verifyRememberMe();
            if($rl)
            {
                $this->sessionInstance->write("RL", $rl);
            }
            elseif($admin) {
                $this->sessionInstance->write("admin", $admin);
            }else
            {
                $route = $this->router->pathFor("login.get");
                $response = $response->withRedirect($route);
                return $response;
            }
        }
        $response = $next($request, $response);
        //après
        return $response;
    }
}