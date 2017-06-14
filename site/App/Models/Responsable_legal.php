<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 22/05/17
 * Time: 23:39
 */

namespace App\Models;


use App\Utils\Utils;

class Responsable_legal extends Models
{

    protected $champs=array(
        "id_responsable_legal"=>"integer",
        "nom_rl"=>"string",
        "prenom_rl"=>"string",
        "adresse_mail_rl"=>"string",
        "ville"=>"string",
        "code_postal"=>"string",
        "complement_d_adresse"=>"string",
        "mot_de_passe_rl"=>"string"
    );

    protected $champsMany=array(
        "id_enfant"=>"integer",
        "id_responsable_legal"=>"integer"
    );

    /**
     * @param string $email de l'utilisateur
     * @param string $mot_de_passe saisie par l'utilisateur
     * @return int -2 si l'adresse n'est pas dans le base de donnée
     * @return int -1 si le mot de passe n'est pas le bon
     * @return int >0 l'id du responsable legal
     */
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

    /**
     * Fonction qui permet de trouver les id des enfants liés au responsable légal
     * @param int $id_rl
     * @return array
     */
    public function trouve_enfants($id_rl)
    {
        return $this->execute("SELECT id_enfant FROM est_responsable_de WHERE id_responsable_legal = $id_rl")->fetchAll();
    }

    /**
     *fonction peremettant de réxuperer les information dur un RL
     *
     * @param int $id du responsable legal
     * @return array contenant les, les clés sont les noms des colonnes
     */
    public function  recupèreInfoParent($id){
        return ($this->select(array("id_responsable_legal"=>$id)))[0];
    }

    /**
     *fonction peremettant de réxuperer les information dur un RL
     *
     * @param int $id du responsable legal
     * @return array contenant les, les clés sont les noms des colonnes
     */
    public function  insertResponsable($data){
        return array("message"=>$this->insert($data));
    }
}