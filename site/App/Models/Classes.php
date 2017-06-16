<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 09:44
 */

namespace App\Models;


class Classes extends Models
{
    protected $champs=array(
        "id_classes"=>"integer",
        "nom_classes"=>"string",
        "annee"=>"string",
        "enseignant"=>"string",
        "id_ecole"=>"integer"
    );


    public function getNomClasse(){
        $res= $this->select("1=1");
        $tab=array();
        foreach ($res as $k=>$v) {
            array_push($tab,array('nom_classes'=>$v['nom_classes'],'id_classes'=>$v['id_classes']));
        }
        return $tab;
    }



}