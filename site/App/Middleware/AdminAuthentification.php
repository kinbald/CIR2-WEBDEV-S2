<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 15/06/17
     * Time: 11:03
     */
    
    namespace App\Middleware;
    
    
    use App\Models\Admin;
    use App\Models\Token_Admin;
    use App\Models\Token_responsable_legal;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use Slim\Router;

    /**
     * @property Router router
     */
    class AdminAuthentification extends Middleware
    {
        public function __invoke(Request $request, Response $response,  callable $next)
        {
            if($this->sessionInstance->read("admin") > 0)
            {
                $this->view->getEnvironment()->addGlobal('infoUtilisateur',(new Admin())->recupèreInfoAdmin($this->sessionInstance->read('admin')));
            }
            else if($this->sessionInstance->read("RL") >0)
            {
                //verifie co utilisateur lambda
                return $response->withRedirect($this->router->pathFor('index'));
            }
            else
            {
                //verifie si les cookies permettent une connection automatique
                $rl=(new Token_responsable_legal())->verifyRememberMe();
                $admin=(new Token_Admin())->verifyRememberMe();
                if($rl)
                {
                    $this->sessionInstance->write("RL", $rl);
                    return $response->withRedirect($this->router->pathFor('index'));
                }
                elseif($admin) {
                    $this->sessionInstance->write("admin", $admin);
                    $this->view->getEnvironment()->addGlobal('infoUtilisateur',(new Admin())->recupèreInfoAdmin($this->sessionInstance->read('admin')));
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