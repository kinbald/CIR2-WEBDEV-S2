<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 22/05/17
 * Time: 23:39
 */

namespace App\Models;


class Responsable_legal extends Models
{

    protected $champs=array(
        "id_responsable_legal"=>"integer",
        "nom_rl"=>"string",
        "prenom_rl"=>"string",
        "adresse_mail_rl"=>"mail",
        "ville"=>"string",
        "code_postal"=>"integer",
        "complement_d_adresse"=>"string",
        "mot_de_passe_rl"=>"string"
    );

    public function authentification_rl($email,$mot_de_passe)
    {
        $hash=$this->select(array("adresse_mail_rl"=>$email));
        $hash=$hash[0];
        if(empty($hash))
        {
            return -2;
        }
        if( password_verify($mot_de_passe,$hash["mot_de_passe_rl"])){
            return $hash["id_responsable_legal"];
        }else{
            return -1;
        }
    }

}