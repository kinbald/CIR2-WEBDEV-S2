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
    }