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
use App\Models\Planning;
use Slim\Http\Request;
use Slim\Http\Response;
use PHPExcel;
use PHPExcel_IOFactory;

class ExportDataController extends Controllers
{

    public function getExportData(Request $request, Response $response)
    {
        $ecole = new Ecole();
        $data = array("1" => "1");
        $listeEcole = $ecole->select($data);
        return $this->view->render($response, 'exportData.twig', ['listeEcole' => $listeEcole]);
    }

    public function postExportData(Request $request, Response $response)
    {
        $params = $request->getParams();
        if ($this->checkInput($params, 'nom_ecole') && $this->checkInput($params,'nom_classe')) {
            if ($params['nom_ecole'] != 0 && $params['nom_classe'] == 0) {
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
                    var_dump($tmp);
                }

                //return $response->withJson($json);
            } else if ($params['nom_ecole'] != 0 && $params['nom_classe'] != 0 && $this->checkInput($params, 'date_journee') ) {
                $classe = new Classes();
                $infoClasse = $classe->select(["nom_ecole" => $params['nom_ecole']]);
                $dataRequete = array(
                    'id_classes' => $infoClasse[0]['id_classes'],
                    'date_journee' => $params['date_journee']
                );
                $planning = new Planning();
                $listeEleves = $planning->select($dataRequete);
                $json = array();
                foreach ($listeEleves as $eleve) {
                    $tmp = array(
                        'nom_eleve' => $eleve['nom_eleve'],
                        'prenom_eleve' => $eleve['prenom_eleve'],
                        'intitule' => $eleve['intitule'],
                    );
                    array_push($json, $tmp);
                    var_dump($tmp);
                }
                $objPHPExcel = new PHPExcel();
                $objWorksheet = $objPHPExcel->getActiveSheet();
                $objWorksheet->fromArray($json);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('excel/'.$params['nom_classe'].'.xls');

               return $response->withJson($json);
            }
            return $response;

        }
    }

    private function checkInput($params, $name)
    {
        return isset($params[$name]) && !empty($params[$name]);
    }
}