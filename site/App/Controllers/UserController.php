<?php
/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 28/05/17
 * Time: 19:23
 */

namespace App\Controllers;

use App\Models\Admin;
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndex(Request $request, Response $response, $args)
    {
        $user = $this->sessionInstance->read("RL");
        $childs = (new Est_responsable_de())->id_enfant_depuis_id_rl($user);
        $childs_names = array();
        foreach ($childs as $child => $key) {
            $info["prenom"]=(new Enfant())->getPrenom($key);
            $info["id"]=$key;
            $childs_names[]=$info;
        }
        $args['enfants'] = $childs_names;
        $args["infoUtilisateur"] = (new Responsable_legal())->recupèreInfoParent($this->sessionInstance->read('RL'));
        return $this->view->render($response, 'index.twig', $args);
    }
    /**
     * Fonction qui gère la page d'accueil d'un admin
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndexAd(Request $request, Response $response, $args)
    {
        $user = $this->sessionInstance->read("admin");

        $args["infoUtilisateur"] = (new Admin())->recupèreInfoAdmin($this->sessionInstance->read('admin'));
        var_dump($args);
        return $this->view->render($response, 'index-admin.twig', $args);
    }
}