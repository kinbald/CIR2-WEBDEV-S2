<?php
/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 28/05/17
 * Time: 19:23
 */

namespace App\Controllers;


use App\Models\Admin;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Swift_IoException;
use Swift_Message;

class ContactController extends Controllers
{
    public function getContact(Request $request, Response $response)
    {
        $user = $this->sessionInstance->read("RL");
        $admin =$this->sessionInstance->read('admin');
        if (!empty($user)) {
            $this->view->getEnvironment()->addGlobal('infoUtilisateur',(new Responsable_legal())->recupèreInfoParent($user));
        }else if(!empty($admin))
        {
            $this->view->getEnvironment()->addGlobal('infoUtilisateur',(new Admin())->recupèreInfoAdmin($admin));
        }
        return $this->view->render($response, 'contact.twig');
    }

    //todo vrai adresse d'envoie + vrai message
    public function postContact(Request $request, Response $response)
    {
        // Tableau qui contiendra les erreurs
        $errors = array();
        // Récupération des paramètres
        $post = $request->getParams();
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
        } else {
            try {
                $message = Swift_Message::newInstance()
                    //emetteur
                    ->setFrom(array('testleasen@gmail.com' => 'leasen'))
                    //destinataire
                    ->setTo("misterplaymania@gmail.com")
                    //sujet
                    ->setSubject("Page de contact: " . $post['objet'])
                    //corps du text
                    ->setBody("<div> Message reçu de la part de  : " . $post['nom'] . " " . $post['prenom'] . "
                            :</br>" . $post['message'] . "</div></br> Contact Mail : " . $post['email'])
                    ->setContentType("text/html; charset=\"UTF-8\"");
                $this->container->mailer->send($message);
            } catch (Swift_IoException $e) {
                echo $e;
            }
            $args["valid"] = "envoie reussie";
        }

        $user = $this->sessionInstance->read("RL");
        $admin =$this->sessionInstance->read('admin');
        if (!empty($user)) {
            $this->view->getEnvironment()->addGlobal('infoUtilisateur',(new Responsable_legal())->recupèreInfoParent($user));
        }else if(!empty($admin))
        {
            $this->view->getEnvironment()->addGlobal('infoUtilisateur',(new Admin())->recupèreInfoAdmin($admin));
        }

        // Il y a des erreurs on les garde dans la session pour l'affichage
        //todo affichage des erreurs?
        $this->sessionInstance->write('errors', $errors);
        // Redirection vers le formulaire
        return $this->view->render($response, 'contact.twig');
    }


}