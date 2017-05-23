<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 23/05/17
 * Time: 15:01
 */

namespace App\Controllers;

use App\Models\Responsable_legal;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * Class AuthController
 * @property Twig view
 * @package App\Controllers
 */
class AuthController extends Controllers
{
    /**
     * Fonction qui gère un appel en POST sur la page de connexion
     * @param Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function postLogin(Request $request, Response $response)
    {
        var_dump($request->getParams());
        $post = $request->getParams();

        if(isset($post['email']) && isset($post['password']))
        {
            if(!empty($post['email']) && !empty($post['password']))
            {
                $etat = (new Responsable_legal($this->container))->authentification_rl($post['email'], $post['password']);
                if($etat == -1)
                {
                    // Le mot de passe est incorect
                    return $this->view->render($response, 'login.twig');
                }
                elseif ($etat == -2)
                {
                    // L'utilisateur n'existe pas
                }
                else
                {
                    // Réussi

                }
            }
        }
        die();
    }

    /**
     * Fonction qui gère un appel en GET sur la page de connexion
     * @param Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function getLogin(Request $request, Response $response)
    {
        return $this->view->render($response, 'login.twig');
    }
}