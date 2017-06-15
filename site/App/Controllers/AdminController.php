<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 14/06/17
     * Time: 15:58
     */
    
    namespace App\Controllers;

    use App\Models\Responsable_legal;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use Slim\Router;
    use Slim\Views\Twig;

    /**
     * Class AdminController
     * @property Twig view
     * @property Router router
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
        
        public function getUserByName(Request $request, Response $response)
        {
            $params = $request->getParams();
            if(isset($params['nom_rl']) && !empty($params['nom_rl']))
            {
                $RLModel = new Responsable_legal();
                $data = $RLModel->recupereRL($params['nom_rl']);
                $json = array();
                foreach ($data as $datum) {
                    $element['id_responsable_legal'] = $datum['id_responsable_legal'];
                    $element['nom_rl'] = $datum['nom_rl'];
                    $element['prenom_rl'] = $datum['prenom_rl'];
                    array_push($json, $element);
                }
                return $response->withJson($json);
            }
            return $response;
        }
        
        public function getModifierRL(Request $request, Response $response)
        {
            $id_rl = $request->getAttribute('id_responsable_legal');
            $ModelRL = new Responsable_legal();
            if($ModelRL->estExistant($id_rl))
            {
                $data = $ModelRL->select(array('id_responsable_legal' => intval($id_rl)));
                return $this->view->render($response, 'modifierRL.twig', ['infos' => $data[0]]);
            }
            return $response->withRedirect($this->router->pathFor('index-admin'));
        }
    }