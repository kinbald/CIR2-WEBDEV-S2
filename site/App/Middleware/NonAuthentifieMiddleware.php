<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 15/06/17
 * Time: 23:02
 */

namespace App\Middleware;


use App\Models\Token_Admin;
use App\Models\Token_responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

/**
 * @property Router router
 */
class NonAuthentifieMiddleware extends Middleware
{

    public function __invoke(Request $request, Response $response,  callable $next)
    {
        if($this->sessionInstance->read("admin") > 0)
        {
            return $response->withRedirect($this->router->pathFor("index-admin"));
        }
        else if($this->sessionInstance->read("RL") >0)
        {
            return $response->withRedirect($this->router->pathFor("index"));
        }
        else
        {
            //verifie si les cookies permettent une connection automatique
            $rl=(new Token_responsable_legal())->verifyRememberMe();
            $admin=(new Token_Admin())->verifyRememberMe();
            if($rl)
            {
                $this->sessionInstance->write("RL", $rl);
                return $response->withRedirect($this->router->pathFor("index"));

            }
            elseif($admin) {
                $this->sessionInstance->write("admin", $admin);
                return $response->withRedirect($this->router->pathFor("index-admin"));
            }
        }
        $response = $next($request, $response);
        //après
        return $response;
    }

}