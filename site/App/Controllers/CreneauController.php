<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 13/06/17
     * Time: 10:15
     */
    
    namespace App\Controllers;
    
    
    use App\Models\Activite;
    use App\Models\ActiviteEnfant;
    use App\Models\Creneau;
    use App\Models\Enfant;
    use App\Models\Est_responsable_de;
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
                $json = array();
                foreach ($creneaux as $creneau) {
                    $intitule = (new Activite())->getIntitule($creneau['id_activite']);
                    $classname = (new Activite())->getClassname($creneau['id_activite']);
                    $tmp = array(
                        'date' => $creneau['date_journee'],
                        'classname' => $classname,
                        'title' => $intitule
                    );
                    array_push($json, $tmp);
                }
                return $response->withJson($json);
            }
            return $response;
        }
        
        private function checkInput($params, $name)
        {
            return isset($params[$name]) && !empty($params[$name]);
        }
        
        /**
         * @param Request $request
         * @param Response $response
         * @return \Psr\Http\Message\ResponseInterface
         *
         * routes d'affichages de la page du calendrier
         */
        public function calendrier(Request $request, Response $response)
        {
            return $this->view->render($response, 'calendrier.twig');
        }
        
        
        /**
         * @param Request $request
         * @param Response $response
         * @return Response
         *
         * route de retour de la requere ajax pour la modification du calendrier
         */
        public function modifieCreneau(Request $request, Response $response, $args)
        {
            $params = $request->getParams();
            if ($this->checkInput($params, 'date') && $this->checkInput($params, 'id_activite')) {
                $Creneau = new Creneau();
                if ($Creneau->ajouteCreneauEnfant(intval($args['id_enfant']), $params['date'], intval($params['id_activite'])) != false) {
                    return $response->withJson(array('Error' => 'false'));
                }
            }
            return $response->withJson(array('Error' => 'Erreur de requête'));
        }
        
        public function getActivite(Request $request,Response $response,$args)
        {
            $anne=$request->getParam('annee');
            $id=$request->getParam('id_enfant');
            $activite=(new ActiviteEnfant())->select(array('annee'=>$anne,'id_enfant'=>$id));
            $res=array();
            $res["activite"]=array();
            foreach ($activite as $v )
            {
                array_push($res["activite"],array('id_activite'=>$v['id_activite'],'intitule' =>$v['intitule'],'classname'=>$v['classname']));
            }
            return $response->withJson($res);
        }
    }