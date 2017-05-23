<?php

namespace App\Controllers;
use App\Models\Admin;
use App\Models\Token_responsable_legal;
use PDO;
use PDOException;
use PHPExcel;
use PHPExcel_IOFactory;
use Swift_IoException;
use Swift_Message;

/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 26/04/17
 * Time: 18:26
 */
class TestController extends Controllers
{

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $args
     * @return mixed
     */
    public function __invoke($request, $response, $args)
    {
        (new Token_responsable_legal($this->container))->verifyRememberMe();
        return $this->view->render($response, 'layout.twig', $args);
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $args
     * @return mixed
     */
    public function salut($request, $response, $args)
    {
        $args['titre'] = 'Un titre';

        return $this->view->render($response, 'test.twig', $args);
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $args
     * @return mixed
     */
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
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("excel/excel.xls");
        return $this->view->render($response, 'layout.twig', $args);
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $args
     * @return mixed
     */
    public function mail($request, $response, $args){
        echo getcwd();
        try {
            $message = Swift_Message::newInstance()
                //emetteur
                ->setFrom(array('testleasen@gmail.com' => 'leasen'))
                //destinataire
                ->setTo(array('william.billaud@isen.yncrea.fr' => 'William Billaud'/*,
                'thomas.artru@isen.yncrea.fr'=>'Thomas Artru',
                'yanis.meziane@isen.yncrea.fr'=>'Yanis Meziane',
                'guillaume.desrumaux@isen.yncrea.fr',
                'felix.herrenschmidt@isen.yncrea.fr'=>'Felix Herrenschmidt'*/))
                //sujet
                ->setSubject('Test swiftmailer')
                //corp du text
                ->setBody('Here is the message itself')
                //piece joint
                ->attach(\Swift_Attachment::fromPath('excel/excel.xls'));
            $this->mailer->send($message);
        }catch (Swift_IoException $e)
        {
            echo $e;
        }
        return $this->view->render($response, 'layout.twig', $args);
    }
}