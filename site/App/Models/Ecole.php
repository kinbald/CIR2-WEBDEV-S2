<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 09:28
 */

namespace App\Models;


class Ecole extends Models
{
    protected $champs=array(
        "id_ecole"=>"integer",
        "nom_ecole"=>"string"
    );


    public function getNomEcole(){
        $res= $this->select("1=1");
        $tab=array();
        foreach ($res as $k=>$v) {
            array_push($tab,array('id_ecole'=>$v['id_ecole'], 'nom_ecole'=>$v['nom_ecole']));
        }
        return $tab;
    }
}