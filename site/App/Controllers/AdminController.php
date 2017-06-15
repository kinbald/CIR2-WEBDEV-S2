<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 14/06/17
     * Time: 15:58
     */
    
    namespace App\Controllers;

    use App\Models\Responsable_legal;
    use App\Utils\Utils;
    use HTML2PDF;
    use HTML2PDF_exception;
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
        
        public function getPasswordImpression(Request $request, Response $response){

            $id_parent=$request->getParam('id_responsable_legal');
            $info=(new Responsable_legal())->select(array('id_responsable_legal'=>$id_parent))[0];
            if(!empty($info))
            {
                //très sale car mdp generer pas forcement valide tout le temp
                do{
                    $nouveauMotDePasse=Utils::generatePassword();
                }while((new Responsable_legal())->modifieMotDePasse($id_parent,$nouveauMotDePasse)==-1);
                try {
                    $html2pdf = new HTML2PDF('P', 'A4', 'fr');
                    $html2pdf->writeHTML('<h2>'.$info["nom_rl"].' '.$info["prenom_rl"].'</h2>');
                    $html2pdf->writeHTML('<p>Identifiant : '.$info["adresse_mail_rl"].'</p>');
                    $html2pdf->writeHTML('<p>Mot de passe : '.$nouveauMotDePasse.'</p>');
                    ob_end_clean();
                    $d=date('Y_M_D');
                    $nom_fichier='pdf/mot_de_passe'.$d.'.pdf';
                    $html2pdf->Output(dirname(__FILE__).'/../../public/'.$nom_fichier,'F');
                } catch (HTML2PDF_exception $e) {
                    echo $e->getMessage();
                    exit;
                }
                return $response->withJson($nom_fichier);
            }
        }
        
        
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