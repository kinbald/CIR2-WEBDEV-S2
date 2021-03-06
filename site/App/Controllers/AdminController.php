<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 14/06/17
     * Time: 15:58
     */
    
    namespace App\Controllers;
    
    use App\Models\Enfant;
    use App\Models\Est_responsable_de;
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
            return $response->withJson(array("error"=>"responsable legal inexistant"));
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
                    $element['path'] = $this->router->pathFor("getModifierRL", ['id_responsable_legal' => $datum['id_responsable_legal']]);
                    array_push($json, $element);
                }
                return $response->withJson($json);
            }
            return $response;
        }

        public function getChildByName(Request $request, Response $response)
        {
            $params = $request->getParams();
            if(isset($params['nom_enfant']) && !empty($params['nom_enfant']))
            {
                $EnfantModel = new Enfant();
                $data = $EnfantModel->recupereEnfant($params['nom_enfant']);
                $json = array();
                foreach ($data as $datum) {
                    $element['id_enfant'] = $datum['id_enfant'];
                    $element['nom_enfant'] = $datum['nom_enfant'];
                    $element['prenom_enfant'] = $datum['prenom_enfant'];
                    $element['path'] = $this->router->pathFor('calendrier', ['id_enfant' => $datum['id_enfant']]);
                    array_push($json, $element);
                }
                return $response->withJson($json);
            }
            return $response;
        }

        public function associe_RL_Enfant(Request $request, Response $response){
            $params = $request->getParams();
            $responsable_de = new Est_responsable_de();
            if(isset($params['parent']) && isset($params['enfant']))
            {
                if ($responsable_de->estReponsable(intval($params['parent']), intval($params['enfant'])) == NULL) {
                    $responsable_de->responsabilise(intval($params['parent']), intval($params['enfant']));
                    $params['valid'] = "L'association a été réalisée avec succès.";
                }else{
                    $params['errors'] = "L'association existe déjà.";
                }
            }
            else
            {
                $params['errors'] = "Vous devez choisir un couple utilisateur / enfant.";
            }
            return $this->view->render($response, 'utilisateur-enfant.twig', $params);
        }
        
        public function getModifierRL(Request $request, Response $response)
        {
            $id_rl = intval($request->getAttribute('id_responsable_legal'));
            $ModelRL = new Responsable_legal();
            if($ModelRL->estExistant($id_rl))
            {
                $data = $ModelRL->select(array('id_responsable_legal' => intval($id_rl)));
                return $this->view->render($response, 'modifierRL.twig', ['infos' => $data[0]]);
            }
            return $response->withRedirect($this->router->pathFor('index-admin'));
        }
        
        public function postModifierRL(Request $request, Response $response)
        {
            // Tableau qui contiendra les erreurs
            $errors = array(null);
            // Récupération des paramètres
            $post = $request->getParams();
            $id_responsable_legal = explode('/', $request->getUri()->getPath())[3];
            
            if (isset($post['nom_rl']) && isset($post['prenom_rl']) && isset($post['adresse_mail_rl']) && isset($post['ville']) && isset($post['code_postal']) && isset($post['complement_d_adresse']))
            {
                if (!empty($post['nom_rl']) && !empty($post['prenom_rl']) && !empty($post['adresse_mail_rl']) && !empty($post['ville']) && !empty($post['code_postal']) && !empty($post['complement_d_adresse']))
                {
                    $ModelRL = new Responsable_legal();
                    if ( $ModelRL->existeRespo(['id_responsable_legal' => $id_responsable_legal] ) )
                    {
                        $donnees = array(
                            'nom_rl' => $post['nom_rl'],
                            'prenom_rl' => $post['prenom_rl'],
                            'adresse_mail_rl' => $post['adresse_mail_rl'],
                            'ville' => $post['ville'],
                            'code_postal' => $post['code_postal'],
                            'complement_d_adresse' => $post['complement_d_adresse']
                        );
                        if($ModelRL->metAJourDonnees($donnees, "id_responsable_legal = " . $this->pdo->quote($id_responsable_legal) ) != NULL)
                        {
                            $errors['success'] = "Vos modifications ont été enregistrées";
                        }
                        else
                        {
                            $errors['danger'] = "Problème lors de la modification des données";
                        }
                    }
                    else
                    {
                        return $response->withRedirect($this->router->pathFor('index-admin'));
                    }
                }
                else
                {
                    $errors['warning'] = "Vous devez remplir tous les champs";
                }
            }
            else
            {
                $errors['warning'] = "Vous devez remplir tous les champs";
            }
            $_SESSION['messages'] = $errors;
            return $response->withRedirect($this->router->pathFor('getModifierRL', ['id_responsable_legal' => $id_responsable_legal]));
        }

        public function utilisateurEnfant(Request $request, Response $response, $args){
            return $this->view->render($response, 'utilisateur-enfant.twig', $args);
        }
        
        public function chercherEnfant(Request $request, Response $response)
        {
            return $this->view->render($response, 'adminChercheEnfant.twig');
        }
    }