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
use App\Models\Est_dans_classes;
use App\Models\Est_responsable_de;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class EnfantController extends Controllers
{
    public function getEnfant(Request $request, Response $response)
    {
        $args['classes']=(new Classes())->getNomClasse();
        //var_dump($args['classes']);
        return $this->view->render($response, 'enfant-admin.twig',$args);
    }

    public function postEnfant(Request $request, Response $response)
    {
        // Tableau qui contiendra les erreurs
        $errors = array();
        $args['classes']=(new Classes())->getNomClasse();
        // Récupération des paramètres
        $post = $request->getParams();
        if (empty($post['nom_enfant']) || empty($post['prenom_enfant'] || empty($post['date_naissance_enfant']) || empty($post['id_classes']))) {
            $errors['erreur'] = "Tous les champs sont obligatoires";
        } else {
            $p=array('nom_enfant'=>$post['nom_enfant'],'prenom_enfant'=>$post['prenom_enfant'],'date_naissance_enfant'=>$post['date_naissance_enfant']);

            if(empty((new Enfant())->select($p))){
                (new Enfant())->insert($p);
                $id=(new Enfant())->select($p)[0]['id_enfant'];
                if(!empty($id))
                {
                    (new Est_dans_classes())->insert(array('id_enfant'=>$id,'id_classes'=>intval($post['classe'])));
                }
            }else{
                $errors['duplication']="une classe identique existe déjà";
            }

        }
        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire


        return $this->view->render($response, 'enfant-admin.twig',$args);

    }

}