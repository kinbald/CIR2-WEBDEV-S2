<?php
/**
 * Created by PhpStorm.
 * User: mania
 * Date: 15/06/17
 * Time: 19:56
 */

namespace App\Controllers;

use App\Models\Classes;
use App\Models\Admin;
use App\Models\Ecole;
use App\Models\Enfant;
use App\Models\Est_responsable_de;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class EnfantController extends Controllers
{
    public function getEnfant(Request $request, Response $response){
        return $this->view->render($response, 'enfant-admin.twig');
    }

    public function postEnfant(Request $request, Response $response){
        // Tableau qui contiendra les erreurs
        $errors = array(null);
        // Récupération des paramètres
        $post = $request->getParams();
        if(isset($post['nom_enfant']) && isset($post['prenom_enfant']) && isset($post['date_naissance_enfant'])){
            if(empty($post['nom_enfant']) || empty($post['prenom_enfant'] || empty($post['date_naissance_enfant']))) {
                $errors['erreur'] = "Tous les champs sont obligatoires";
            } else{

                $classe = new Enfant();
                $classe->insertEnfant($post['nom_enfant'], $post['prenom_enfant'], $post['date_naissance_enfant']);
                return $response->withRedirect($this->router->pathFor('index-admin'));
            }

        }

        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        return $response->withRedirect($this->router->pathFor('enfant-admin.get'));

    }

}