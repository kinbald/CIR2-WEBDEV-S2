<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 23/05/17
 * Time: 15:01
 */

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Token_Admin;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

/**
 * Class AuthController
 * @property Twig view
 * @property Router router
 * @package App\Controllers
 */
class AuthAdminController extends Controllers
{
    /**
     * Fonction qui gère un appel en POST sur la page de connexion
     * @param Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function postLoginAd(Request $request, Response $response)
    {
        // Tableau qui contiendra les erreurs
        $errors = array(null);
        // Récupération des paramètres
        $post = $request->getParams();

        if (isset($post['email']) && isset($post['password'])) {
            if (!empty($post['email']) && !empty($post['password'])) {
                $etat = (new Admin())->authentification_admin($post['email'], $post['password']);
                if ($etat == -1) {
                    // Le mot de passe est incorrect
                    $errors['password'] = "Le mot de passe est incorrect.";
                } elseif ($etat == -2) {
                    // L'utilisateur n'existe pas
                    $errors['email'] = "Cet admin n'existe pas.";
                } elseif ($etat > 0) {
                    // Connexion réussie
                    $this->connectUserAd($etat);
                    if ($post["remember"]) {
                        (new Token_Admin())->setRememberMe($etat);
                    }
                    // Redirection vers l'index
                    return $response->withRedirect($this->router->pathFor('index'));
                }
            }
            else
            {
                $errors['champs'] = "Les champs doivent être remplis.";
            }
        }
        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        return $response->withRedirect($this->router->pathFor('login-admin.get'));
        //return $this->view->render($response, 'login.twig'/*, ['errors' => $errors]*/);
    }

    /**
     * Fonction qui gère un appel en GET sur la page de connexion
     * @param Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function getLoginAd(Request $request, Response $response)
    {
        return $this->view->render($response, 'login-admin.twig');
    }

    /**
     * Fonction permettant de déconnecter un admin
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function logoutAd(Request $request, Response $response, $args)
    {
        (new Token_Admin())->unsetRememberMe();
        $this->sessionInstance->delete("admin");
        return $response->withRedirect($this->router->pathFor('index'));
    }

    /**
     * Fonction qui affiche la page de récupération de mot de passe
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function recoverAd(Request $request, Response $response, $args)
    {
        // Envoi d'un mail avec le token de regénération du mot de passe
        return $this->view->render($response, 'recover-admin.twig');
    }

    /**
     * Fonction qui envoie le mail et insère le token dans la base de données pour pouvoir retrouver son mot de passe
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function sendRecoverAd(Request $request, Response $response, $args)
    {
        if ($request->getParam('email'))
        {
            if(!empty($request->getParam('email')))
            {
                (new Token_Admin())->setTokenRecovery($request->getParam('email'));
                $args["send"] = true;
            }
        }
        $args["send"] = false;
        return $this->view->render($response, 'recover-admin.twig', $args);
    }

    /**
     * TODO Add comments
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function tokenAd(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'newPassword-admin.twig', $args);
    }

    /**
     * TODO Add comments
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function tokenValidationAd(Request $request, Response $response, $args)
    {
        //var_dump($args);
        //todo ->solidité mot de passe?
        if ($request->getParam('password') != $request->getParam('confirmation')) {
            $args["statut"] = "motDePasseDifferent";
        } else {
            //var_dump($request->getParams());
            $id = (new Token_Admin())->existeTokenRecover($args["token"]);
            if ($id > 0) {
                (new Admin())->update(array(
                    "mot_de_passe" => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
                ), "id_admin =" . $id);
                (new Token_Admin())->unsetAllRememberMe($id);
                $args["statut"] = "ok";
            } else {
                $args["statut"] = "tokenAbsent";
            }
        }
        // Mise à jour du mot de passe
        return $this->view->render($response, 'newPassword-admin.twig', $args);
    }

    /**
     * Fonction permettant de connecter un utilisateur dans la session
     * @param int $etat
     */
    public function connectUserAd($etat)
    {
        if($etat)
        {
            $this->sessionInstance->write("admin", $etat);
        }
    }
}