<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 23/05/17
 * Time: 15:01
 */

namespace App\Controllers;

use App\Models\Responsable_legal;
use App\Models\Token_responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 * @package App\Controllers
 */
class AuthController extends Controllers
{
    /**
     * Fonction qui gère un appel en POST sur la page de connexion
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function postLogin(Request $request, Response $response)
    {
        $post = $request->getParams();
        $_SESSION["RL"] = (new Responsable_legal())->authentification_rl($post["email"], $post["password"]);
        //si l'utilisateur a pu se connecter
        if ($_SESSION["RL"] > 0) {
            if ($post["remember"]) {
                (new Token_responsable_legal())->setRememberMe($_SESSION["RL"]);
            }
                //il est redirige vers l'index
            return $response->withHeader('Location','index');
        }else{
            //sinon la page de login lui est reapparait
            unset($_SESSION["RL"]);
            return $this->view->render($response, 'login.twig');
        }
    }

    /**
     * Fonction qui gère un appel en GET sur la page de connexion
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function getLogin(Request $request, Response $response)
    {
        return $this->view->render($response, 'login.twig');
    }
}