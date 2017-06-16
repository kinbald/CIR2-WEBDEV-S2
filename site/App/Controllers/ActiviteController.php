<?php
/**
 * Created by PhpStorm.
 * User: mania
 * Date: 16/06/17
 * Time: 01:27
 */

namespace App\Controllers;

use App\Models\Activite;
use Slim\Http\Request;
use Slim\Http\Response;


class ActiviteController extends Controllers
{
    public function getActivite(Request $request, Response $response)
    {
        return $this->view->render($response, 'activite-admin.twig');
    }

    public function postActivite(Request $request, Response $response)
    {
        // Tableau qui contiendra les erreurs
        $errors = array();
        // Récupération des paramètres
        $post = $request->getParams();
        if (empty($post['intitule']) || empty($post['classname'] || empty($post['date_naissance_enfant']))) {
            $errors['erreur'] = "Tous les champs sont obligatoires";
        } else {
            $p=array('intitule'=>$post['intitule'],'classname'=>$post['classname']);
            if(empty((new Activite())->select($p))){
                (new Activite())->insert($p);
            }else{
                $errors['duplication']="une classe identique existe déjà";
            }

            return $this->view->render($response, 'activite-admin.twig');
        }


        // Il y a des erreurs donc on les garde dans la session pour l'affichage
        $_SESSION['errors'] = $errors;
        // Redirection vers le formulaire
        return $this->view->render($response, 'activite-admin.twig');

    }

}