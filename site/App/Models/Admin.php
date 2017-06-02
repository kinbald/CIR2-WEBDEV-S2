<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 17/05/17
 * Time: 21:02
 */

namespace App\Models;


class Admin extends Models
{
    protected $champs=array("type_droit"=>"integer","mot_de_passe"=>"string","adresse_mail"=>"mail","id_admin"=>"index");

    /**
     * @param string $email email de l'utilisateur
     * @param string $mot_de_passe de l'utilisateur
     * @return int type de droit de l'utilisateur si il existe
     * @return int -2 si l'email n'existe pas dans la bdd
     * @return int -1 si le couple est invalide
     */
    public function authentification_admin($email,$mot_de_passe)
    {
        $hash=$this->select(array("adresse_mail"=>$email));
        $hash=$hash[0];
        if(empty($hash))
        {
            return -2;
        }
        if( password_verify($mot_de_passe,$hash["mot_de_passe"])){
            return $hash["type_droit"];
        }else{
            return -1;
        }
    }

    /**
     * @param string $email de l'utilisateur
     * @param string $mot_de_passe de l'utilisateur
     * @param int $type_droit entier correspondant au droit
     * @return bool|\PDOStatement
     */
    public function ajout_admin($email,$mot_de_passe,$type_droit){
        //todo verification auteur requete droit superieur!
        $data=array(
            "adresse_mail"=>$email,
            "mot_de_passe"=>password_hash($mot_de_passe,PASSWORD_DEFAULT),
            "type_droit" =>$type_droit
        );
        return $this->insert($data);
    }
}