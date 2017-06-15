<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 14/06/17
     * Time: 15:58
     */
    
    namespace App\Controllers;

    use Slim\Http\Request;
    use Slim\Http\Response;
    use Slim\Views\Twig;

    /**
     * Class AdminController
     * @property Twig view
     * @package App\Controllers
     */
    class AdminController extends Controllers
    {
        public function getAdminRegenerer(Request $request, Response $response)
        {
            return $this->view->render($response, 'adminRegeneration.twig');
        }
        
        public function regenererCompte(Request $request, Response $response)
        {
        
        }
        
        public function getPasswordImpression(){}
    }