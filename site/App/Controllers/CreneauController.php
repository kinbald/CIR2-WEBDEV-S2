<?php
/**
 * Created by PhpStorm.
 * User: kinbald
 * Date: 13/06/17
 * Time: 10:15
 */

namespace App\Controllers;


use App\Models\Creneau;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

/**
 * Class CreneauController
 * @property Twig view
 * @property Router router
 * @package App\Controllers
 */
class CreneauController extends Controllers
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getMoisEnfant(Request $request, Response $response)
    {
        $params = $request->getParams();
        $id_enfant = $request->getAttribute('id_enfant');
        if ($this->checkInput($params, 'mois') && $this->checkInput($params, 'annee')) {
            $creneaux = (new Creneau())->getCreneauxMois($params['annee'], $params['mois'], $id_enfant);
            return $response->withJson($creneaux);
        }
    }


    public function checkInput($params, $name)
    {
        return isset($params[$name]) && !empty($params[$name]);
    }

    public function calendrier(Request $request, Response $response, $args)
    {
        return $this->view->render($response,'calendrier.twig');
    }
}