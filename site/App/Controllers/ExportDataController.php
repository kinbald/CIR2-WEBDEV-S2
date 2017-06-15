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
use Slim\Views\Twig;

/**
 * @property Twig view
 */
class ExportDataController extends Controllers
{

    public function getExportData(Request $request, Response $response)
    {
        $ecole = new Ecole();
        $data = array("1" => "1");
        $listeEcole = $ecole->select($data);
        return $this->view->render($response, 'exportData.twig', ['listeEcole' => $listeEcole]);
    }


    public function exportDataGetClasses(Request $request, Response $response)
    {
        $ecole = new Ecole();
        $infoEcole = $ecole->select(["nom_ecole" => $request->getParam('nom_ecole')]);
        $classes = new classes();
        $listeClasses = $classes->select(["id_ecole" => $infoEcole[0]["id_ecole"]]);
        $json = array();
        foreach ($listeClasses as $listeClass) {
            $json[$listeClass['id_classes']] = $listeClass['nom_classes'];
        }
        return $response->withJson($json);
    }

    public function exportDataGetPlanning(Request $request, Response $response)
    {
        $ecole = new Ecole();
        $infoEcole = $ecole->select(["nom_ecole" => $request->getParam('nom_ecole')]);
        if ($infoEcole[0]['id_ecole'] == '') return $response;
        $classe = new Classes();
        $infoClasse = $classe->select(["nom_classes" => $request->getParam('nom_classe'),
            "id_ecole" => $infoEcole[0]["id_ecole"]]);
        if ($infoClasse[0]['id_classes'] == '') return $response;
        date_default_timezone_set('UTC');
        $date = date("Y-m-d");
        $dataRequete = array(
            'id_classes' => $infoClasse[0]['id_classes'],
            'date_journee' => $date
        );
        $planning = new Planning();
        $listeEleves = $planning->select($dataRequete);
        $json = array();
        //var_dump($listeEleves);
        foreach ($listeEleves as $eleve) {
            $tmp = array(
                'nom_enfant' => $eleve['nom_enfant'],
                'prenom_enfant' => $eleve['prenom_enfant'],
                'intitule' => $eleve['intitule'],
            );
            array_push($json, $tmp);
        }
        //var_dump($json);
        //Creation du fihier exel
        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet->fromArray(array('ARTRU'=>'Thomas'),null,A1,false);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("../../public/excel/monfichierexcel.xlsx");


        return $response->withJson($json);
    }

}