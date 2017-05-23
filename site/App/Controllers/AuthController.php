<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 23/05/17
 * Time: 15:01
 */

namespace App\Controllers;

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
     */
    public function postLogin(Request $request, Response $response)
    {
        var_dump($request->getParams());
        die();
    }

    /**
     * Fonction qui gère un appel en GET sur la page de connexion
     * @param Request $request
     * @param Response $response
     */
    public function getLogin(Request $request, Response $response)
    {
        return $this->view->render($response, 'login.twig');
    }
}