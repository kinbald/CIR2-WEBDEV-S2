<?php
/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 28/05/17
 * Time: 19:23
 */

namespace App\Controllers;

use App\Models\Enfant;
use App\Models\Est_responsable_de;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * Class UserController
 * @property Twig view
 * @package App\Controllers
 */
class UserController extends Controllers
{
    /**
     * Fonction qui gère la page d'accueil d'un responsable légal
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndex(Request $request, Response $response, $args)
    {
        $user = $this->sessionInstance->read("RL");
        if($user)
        {
            $childs = (new Est_responsable_de())->id_enfant_depuis_id_rl($user);
            $childs_names = array();
            foreach ($childs as $child => $key) {
                $info["prenom"]=(new Enfant())->getPrenom($key);
                $info["id"]=$key;
                $childs_names[]=$info;
            }
            $args['enfants'] = $childs_names;
        }
        return $this->view->render($response, 'index.twig', $args);
    }

    /**
     * Fonction qui gère la page d'accueil d'un admin
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndexAd(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'index-admin.twig', $args);
    }

    public function getCompte(Request $request, Response $response){
        return $this->view->render($response, 'compte.twig');
    }

    public function postCompte(Request $request, Response $response){
        //var_dump($args);

        $id = $this->sessionInstance->read("RL");
        //todo ->solidité mot de passe?
        if ($request->getParam('new_pass') != $request->getParam('confirm_new_pass')) {
            $args["statut"] = "motDePasseDifferent";
        } else {
            //var_dump($request->getParams());
            (new Responsable_legal())->update(array(
                "mot_de_passe_rl" => password_hash($request->getParam('new_pass'), PASSWORD_DEFAULT)
            ), "id_responsable_legal =" . $id);
            $args["statut"] = "ok";
        }
        // Mise à jour du mot de passe
        return $this->view->render($response, 'compte.twig', $args);
    }
}