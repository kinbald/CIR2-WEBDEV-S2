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

    public function insertClasse( $nom_classe, $annee, $enseignant, $id_ecole)
    {

        if (!empty($nom_classe) && !empty($id_classe)) {
            $this->insert(array("nom_classes" => $nom_classe,
                "annee" => $annee,
                "enseignant" => $enseignant,
                "id_ecole" => $id_ecole));
        }
    }

    public function getNomClasse(){
        $res= $this->select("1=1");
        $tab=array();
        foreach ($res as $k=>$v) {
            array_push($tab,array('id_ecole'=>$v['id_ecole'], 'nom_classes'=>$v['nom_classes']));
        }
        return $tab;
    }



}