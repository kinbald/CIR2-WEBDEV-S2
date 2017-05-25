<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 25/05/17
 * Time: 17:57
 */
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * Class ValidationErreursMiddleware
 * @property Twig view
 * @package App\Middleware
 */
class ValidationErreursMiddleware extends Middleware
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // On ajoute dans le container les erreurs récupérées dans la session
        $this->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
        // On vide la session du tableau d'erreurs
        unset($_SESSION['errors']);
        return $next($request, $response);
    }
}