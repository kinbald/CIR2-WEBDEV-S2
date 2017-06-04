<?php
/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 28/05/17
 * Time: 19:23
 */

namespace App\Controllers;

use App\Models\Enfant;
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
    public function getIndex(Request $request, Response $response,$args)
    {
        $user = $this->sessionInstance->read("RL");
        $RLModel = new Responsable_legal();
        $EnfantsModel = new Enfant();
        $childs = $RLModel->trouve_enfants($user);
        $childs_names = array();
        foreach ($childs as $child => $key) {
            $childs_names[$key['id_enfant']] = $EnfantsModel->getPrenom($key['id_enfant']);
        }
        $args["infoUtilisateur"]=(new Responsable_legal())->recupèreInfoParent($this->sessionInstance->read('RL'));
        return $this->view->render($response, 'index.twig',$args );
    }
}