<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 25/05/17
 * Time: 19:16
 */

namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * Class PersitenceFormulaireMiddleware
 * @property Twig view
 * @package App\Middleware
 */
class PersitenceFormulaireMiddleware extends Middleware
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // On ajoute au container les anciens champs du formulaire
        $this->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
        /* On ajoute dans la session ces champs
         * Cette ligne est avant afin d'être utilisée au passage dans le
         * middleware après validation du formulaire
         */
        $_SESSION['old'] = $request->getParams();
        return $next($request, $response);
    }
}