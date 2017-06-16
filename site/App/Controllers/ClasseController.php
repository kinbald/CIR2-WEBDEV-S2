<?php
/**
 * Created by PhpStorm.
 * User: mania
 * Date: 15/06/17
 * Time: 16:01
 */

namespace App\Controllers;


use App\Models\Classes;
use App\Models\Ecole;
use App\Models\Est_de_niveau;
use App\Models\Section;
use Slim\Http\Request;
use Slim\Http\Response;

class ClasseController extends Controllers{

    public function getClasse(Request $request, Response $response){

        $args['infoEcole'] = (new Ecole())->getNomEcole();
        $args['infoSection'] = (new Section())->getSection();
        return $this->view->render($response, 'classe-admin.twig', $args);
    }

    public function postClasse(Request $request, Response $response){
        // Tableau qui contiendra les erreurs
        $errors = array();
        // Récupération des paramètres
        $post = $request->getParams();
            if(empty($post['nom_classe']) || empty($post['annee'] || empty($post['nom_enseignant']) || empty($post['ecole']) || empty($post['section']))) {
                $errors['erreur'] = "Tous les champs sont obligatoires";
            } else{
                $p=array("nom_classes" => $post['nom_classe'],"annee" => $post['annee'], "enseignant" => $post['nom_enseignant'],"id_ecole" => intval($post['ecole']));
                if(empty((new Classes())->select($p)))
                {
                    (new Classes())->insert($p);
                    $res=(new Classes())->select($p)[0]['id_classes'];
                    //todo verifie id_section
                    if(!empty($res))
                    {
                        (new Est_de_niveau())->insert(array('id_classes'=>$res,'id_section'=>intval($post['section'])));
                    }
                }else{
                    $errors['duplication']="une classe identique existe déjà";
                }
            }

        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        $args['infoEcole'] = (new Ecole())->getNomEcole();
        $args['infoSection'] = (new Section())->getSection();
        return $this->view->render($response, 'classe-admin.twig',$args);

    }
}