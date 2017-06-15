<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 14/06/17
 * Time: 13:54
 */

namespace App\Middleware;


use App\Models\Est_responsable_de;
use Slim\Http\Request;
use Slim\Http\Response;

class VerificationRl extends Middleware
{


    public function __invoke(Request $request, Response $response, callable $next)
    {
        $id_enfant = $request->getAttribute('route')->getArgument('id_enfant');
        if (!$this->sessionInstance->read('admin') > 0) {
            if (!$this->sessionInstance->read('RL') > 0) {
                return $response->withRedirect($this->router->pathFor("login.get"));
            } else if (!(new Est_responsable_de())->estReponsable($this->sessionInstance->read('RL'), $id_enfant)) {
                return $this->view->render($response,'/error/accesRefuse.twig');
            }
        }
        $response = $next($request, $response);
        return $response;
    }
}