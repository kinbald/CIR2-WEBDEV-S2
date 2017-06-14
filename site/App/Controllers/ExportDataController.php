<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 10:38
 */

namespace App\Controllers;
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
        $data="";
        $listeEcole = $ecole->select($data);
        $response->withBody($listeEcole);
        var_dump($listeEcole);
        return $this->view->render($response, 'exportData.twig');
    }

    public function selectClasse(Request $request, Response $response)
    {
        $params = $request->getParams();
        $nomEcole = $params['ecole'];
        if(isset($ecole)){

        }

        return $response->withRedirect($this->router->pathFor('exportData.get'));
    }
  }