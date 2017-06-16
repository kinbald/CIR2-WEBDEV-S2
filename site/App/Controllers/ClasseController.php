<?php
/**
 * Created by PhpStorm.
 * User: mania
 * Date: 15/06/17
 * Time: 16:01
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

class ClasseController extends Controllers{

    public function getClasse(Request $request, Response $response){

        $args['infoEcole'] = (new Ecole())->getNomEcole();
        return $this->view->render($response, 'classe-admin.twig', $args);
    }

    public function postClasse(Request $request, Response $response){
        // Tableau qui contiendra les erreurs
        $errors = array(null);
        // Récupération des paramètres
        $post = $request->getParams();
         if(isset($post['nom_classe']) && isset($post['annee']) && isset($post['nom_enseignant'])){
            if(empty($post['nom_classe']) || empty($post['annee'] || empty($post['nom_enseignant']))) {
                $errors['erreur'] = "Tous les champs sont obligatoires";
            } else{

                $classe = new Classes();
                $classe->insertClasse($post['nom_classe'], $post['annee'], $post['nom_enseignant'], intval($post['selecter_basic']));
                return $response->withRedirect($this->router->pathFor('index-admin'));
            }

        }

        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        return $response->withRedirect($this->router->pathFor('classe-admin.get'));

    }
}