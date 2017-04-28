<?php
namespace App\Controllers;

/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 26/04/17
 * Time: 18:26
 */
class TestController extends Controllers
{

    public function __invoke($request,$response,$args)
    {
        $args['titre']="super page";
        return $this->view->render($response, 'layout.twig', $args);
    }
}