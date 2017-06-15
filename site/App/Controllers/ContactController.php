<?php
/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 28/05/17
 * Time: 19:23
 */

namespace App\Controllers;

use App\Models\Enfant;
use App\Models\Est_responsable_de;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Swift_IoException;
use Swift_Message;

class ContactController extends Controllers
{
    public function getContact(Request $request, Response $response, $args){
        $user = $this->sessionInstance->read("RL");
        $childs = (new Est_responsable_de())->id_enfant_depuis_id_rl($user);
        $childs_names = array();
        foreach ($childs as $child => $key) {
            $info["prenom"]=(new Enfant())->getPrenom($key);
            $info["id"]=$key;
            $childs_names[]=$info;
        }
        $args['enfants'] = $childs_names;
        $args["infoUtilisateur"] = (new Responsable_legal())->recupèreInfoParent($this->sessionInstance->read('RL'));
        return $this->view->render($response, 'contact.twig', $args);
    }

    public function postContact(Request $request, Response $response)
    {
        // Tableau qui contiendra les erreurs
        $errors = array(null);
        // Récupération des paramètres
        $post = $request->getParams();

        if (isset($post['email']) && isset($post['nom']) && isset($post['prenom'])) {
                if (empty($post["email"])) {
                    // Mail vide ?
                    $errors['email'] = "L'e-mail est obligatoire";
                } elseif (empty($post["nom"])) {
                    // nom vide
                    $errors['nom'] = "Précisez votre nom.";
                } elseif (empty($post["prenom"])) {
                    // Prenom vide
                    $errors['prenom'] = "Précisez votre nom.";
                } elseif (empty($post["objet"])) {
                    // Prenom vide
                    $errors['objet'] = "Précisez l'objet de votre message.";
                } else{
                    try {
                        $message = Swift_Message::newInstance()
                            //emetteur
                            ->setFrom(array('testleasen@gmail.com' => 'leasen'))
                            //destinataire
                            ->setTo("misterplaymania@gmail.com")
                            //sujet
                            ->setSubject("Page de contact: " .$post['objet'])
                            //corps du text
                            ->setBody("<div> Message reçu de la part de  : " . $post['nom']. " " . $post['prenom'] . "
                            :</br>" .$post['message'] . "</div></br> Contact Mail : " .$post['email'])
                            ->setContentType("text/html; charset=\"UTF-8\"");
                        $this->container->mailer->send($message);
                    } catch (Swift_IoException $e) {
                        echo $e;
                    }
                    return $response->withRedirect($this->router->pathFor('index'));
                }

            }
            else
            {
                $errors['champs'] = "Les champs doivent être remplis.";

            }

        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        return $response->withRedirect($this->router->pathFor('contact.get'));
        //return $this->view->render($response, 'login.twig'/*, ['errors' => $errors]*/);
    }






}