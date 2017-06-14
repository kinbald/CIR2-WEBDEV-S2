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
        $ecole = $_POST['nom'];
        $classe =$_POST['classe'];

        //return $response->withRedirect($this->router->pathFor('exportData.get'));
    }


    public function getExportData(Request $request, Response $response)
    {
        $ecole= new Ecole();
        $data=array("1"=>"1");
        $listeEcole = $ecole->select($data);
        return $this->view->render($response, 'exportData.twig',['listeEcole' => $listeEcole]);
    }

    public function selectClasse(Request $request, Response $response)
    {
        $params = $request->getParams();
        $nomEcole = $params['ecole'];
        if(isset($nomEcole)){
        $ecole = new Ecole();
        $infoEcole = $ecole->select(["nom_ecole"=>$nomEcole]);
        $classe = new classes();
        $listeClasses= $classe->select(["id_ecole"=>$infoEcole[0]["id_ecole"]]);

        }

        return $response->withRedirect($this->router->pathFor('exportData.get'));
    }
  }