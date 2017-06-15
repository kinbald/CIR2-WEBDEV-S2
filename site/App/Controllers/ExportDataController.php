<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 10:38
 */

namespace App\Controllers;

use App\Models\Classes;
use App\Models\Ecole;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ExportDataController extends Controllers
{

    public function postExportData(Request $request, Response $response)
    {
        return $response->withRedirect($this->router->pathFor('exportData.get'));
    }


    public function getExportData(Request $request, Response $response)
    {
        $ecole = new Ecole();
        $data = array("1" => "1");
        $listeEcole = $ecole->select($data);
        return $this->view->render($response, 'exportData.twig', ['listeEcole' => $listeEcole]);
    }

    public function selectClasse(Request $request, Response $response)
    {
        $params = $request->getParams();
        if ($this->checkInput($params, 'nom_ecole')) {
            $ecole = new Ecole();
            $infoEcole = $ecole->select(["nom_ecole" => $params['nom_ecole']]);
            $classes = new classes();
            $listeClasses = $classes->select(["id_ecole" => $infoEcole[0]["id_ecole"]]);
            $json = array();
            foreach ($listeClasses as $classe) {
                $tmp = array(
                    'nom_classe' => $classe['nom_classe'],
                );
                array_push($json, $tmp);
            }
            return $response->withJson($json);
        }
        return $response;
    }
    public function selectEleve(Request $request, Response $response)
    {
        $params = $request->getParams();
        if ($this->checkInput($params, 'nom_ecole') && $this->checkInput($params, 'nom_classe')) {
            $dataRequete = array(
                'nom_classe' => $params['nom_classe'],
                'nom_ecole' => $params['nom_ecole'],
                'date_journee' =>$params
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