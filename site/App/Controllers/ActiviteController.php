<?php
/**
 * Created by PhpStorm.
 * User: mania
 * Date: 16/06/17
 * Time: 01:27
 */

namespace App\Controllers;

use App\Models\Activite;
use App\Models\Admin;
use App\Models\Ecole;
use App\Models\Enfant;
use App\Models\Est_responsable_de;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class ActiviteController extends Controllers
{
    public function getActivite(Request $request, Response $response){
        return $this->view->render($response, 'activite-admin.twig');
    }

    public function postActivite(Request $request, Response $response){
        // Tableau qui contiendra les erreurs
        $errors = array(null);
        // Récupération des paramètres
        $post = $request->getParams();
        if(isset($post['intitule']) && isset($post['classname'])){
            if(empty($post['intitule']) || empty($post['classname'] || empty($post['date_naissance_enfant']))) {
                $errors['erreur'] = "Tous les champs sont obligatoires";
            } else{

                $activite = new Activite();
                $activite->insertActivite($post['intitule'], $post['classname']);
                return $response->withRedirect($this->router->pathFor('index-admin'));
            }

        }

        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        return $response->withRedirect($this->router->pathFor('activite-admin.get'));

    }

}