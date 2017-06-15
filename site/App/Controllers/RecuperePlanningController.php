<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 15/06/17
 * Time: 10:49
 */

namespace App\Controllers;


class RecuperePlanningController
{
    public function selectEleves(Request $request, Response $response)
    {
        $params = $request->getParams();
        if ($this->checkInput($params, 'nom_classe')) {
            $dataRequete = array(
                'nom_classe' => $params['nom_classe'],
                'date_journee' =>$params['date_journee']
            );
            $adefinir = new adefinir();
            $listeEleves = $adefinir->selet($dataRequete);
            $json = array();
            foreach ($listeEleves as $eleve) {
                $tmp = array(
                    'nom_eleve' => $eleve['nom_eleve'],
                    'prenom_eleve' => $eleve['prenom_eleve'],
                    'intitule' => $eleve['intitule'],
                );
                array_push($json, $tmp);
            }
            return $response->withJson($json);
        }
        return $response;
    }
}