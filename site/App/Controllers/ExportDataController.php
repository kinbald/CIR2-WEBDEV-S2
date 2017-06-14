<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 10:38
 */

namespace App\Controllers;
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
        return $this->view->render($response, 'exportData.twig');
    }
}