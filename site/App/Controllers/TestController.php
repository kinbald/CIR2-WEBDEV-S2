<?php

namespace App\Controllers;
use App\Models\Models;
use PDO;
use PDOException;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 26/04/17
 * Time: 18:26
 */
class TestController extends Controllers
{

    public function __invoke($request, $response, $args)
    {
        $args['titre'] = "super page";
        return $this->view->render($response, 'layout.twig', $args);
    }

    public function salut($request, $response, $args)
    {
        $args['titre'] = 'Un titre';

        return $this->view->render($response, 'test.twig', $args);
    }

    public function excel($request, $response, $args)
    {

        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $req=$this->pdo->prepare("SELECT * FROM test");
        try {
            $req->execute();
        } catch (PDOException $e) {
            if ($this->container->get('settings')["debug"]>0) {
                echo $e->getMessage();
            } else {
                echo 'bdd indispo';
            }
        }
        $objWorksheet->fromArray($req->fetchAll(PDO::FETCH_ASSOC));

// Save Excel 2007 file
        echo date('H:i:s'), " Write to Excel2007 format", EOL;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("excel/excel.xls");
        echo date('H:i:s'), " File written to ", str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;


// Echo memory peak usage
        echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;

        // Echo done
        echo date('H:i:s'), " Done writing file", EOL;
        echo 'File has been created in ', getcwd(), EOL;


        return $this->view->render($response, 'layout.twig', $args);
    }

}