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
use App\Models\Token_responsable_legal;
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
        if (isset($post['email']) && isset($post['password'])) {
            if (!empty($post['email']) && !empty($post['password'])) {
                $etat = (new Responsable_legal())->authentification_rl($post['email'], $post['password']);
                if ($etat == -1) {
                    // Le mot de passe est incorect
                    return $this->view->render($response, 'login.twig');
                } elseif ($etat == -2) {
                    // L'utilisateur n'existe pas
                    return $this->view->render($response, 'login.twig');
                } elseif ($etat > 0) {
                    //reussi
                    $_SESSION["RL"] = $etat;
                    if ($post["remember"]) {
                        (new Token_responsable_legal())->setRememberMe($_SESSION["RL"]);
                    }
                    //il est redirige vers l'index
                    return $response->withHeader('Location', 'index');

                }
            }
        }
        return $this->view->render($response, 'login.twig');
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

    public function logout(Request $request, Response $response, $args)
    {
        (new Token_responsable_legal())->unsetRememberMe();
        unset($_SESSION["RL"]);
        return $response->withHeader('Location', 'index');
    }

    //affiche la page pour rentrer son mail pour pouvoir récuperer son mot de passe
    public function recover(Request $request, Response $response, $args)
    {
        //envoie un mail avec le token de regeneration du mot de passe
        return $this->view->render($response, 'recover.twig');
    }


    //envoie le mail et insère le token dans la base de donnée pour pouvoir retrouver son mot de passe.
    public function sendRecover(Request $request, Response $response, $args)
    {
        (new Token_responsable_legal())->setTokenRecovery($request->getParam('email'));
        $args["send"] = true;
        return $this->view->render($response, 'recover.twig', $args);
    }

    public function token(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'newPassword.twig', $args);
    }

    public function tokenValidation(Request $request, Response $response, $args)
    {
        var_dump($args);
        //todo ->solidité mot de passe?
        if ($request->getParam('password') != $request->getParam('confirmation')) {
            $args["statut"] = "motDePasseDifferent";
        } else {
            var_dump($request->getParams());
            $id = (new Token_responsable_legal())->existeTokenRecover($args["token"]);
            if ($id > 0) {
                (new Responsable_legal())->update(array(
                    "mot_de_passe_rl" => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
                ), "id_responsable_legal =" . $id);
                (new Token_responsable_legal())->unsetAllRememberMe($id);
                $args["statut"] = "ok";
            } else {
                $args["statut"] = "tokenAbsent";
            }
        }

        //update mot de passe

        return $this->view->render($response, 'newPassword.twig', $args);
    }
}