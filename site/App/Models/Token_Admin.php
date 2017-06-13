<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 23/05/17
 * Time: 19:03
 */

namespace App\Models;


class Token_Admin extends Models
{
    protected $champs =array(
        "verifier_admin"=>"string",
        "selector_admin"=>"string",
        "id_admin"=>"integer",
        "date_expiration_admin"=>"date",
    );

    /**
     * @param int $id de l'utilisateur dont nous devons nous souvenir
     *
     * permet de créer un cookie pour se souvenir de l'utilisateur, et l'inserer en memoire.
     */
    public function setRememberMe($id)
    {
        $selector=bin2hex(random_bytes(20));
        $validator=bin2hex(random_bytes(20));
        $hash_c=hash("sha384",$validator);
        $dateExpiration=time()+24*60*60*10;
        setcookie("remembermeA",$selector." | ".$validator,$dateExpiration,null,null,null,true);
        $this->insert(array(
            "verifier_admin"=>$hash_c,
            "selector_admin"=>$selector,
            "date_expiration_admin"=> date("Y/m/d",$dateExpiration),
            "id_admin"=>$id,
        ));
    }

    /**
     * @return bool | int
     * @return false si l'utilisateur n'est pas connecté via un cookie rememberme
     * @return int l'id de l'utilisateur
     *
     * permet de savoir si l'utilisateur a un cookie remeberMe actif et valide
     */
    public function verifyRememberMe()
    {
        if(isset($_COOKIE["remembermeA"]))
        {
            $rememberme=$_COOKIE["remembermeA"];
        }
        else
        {
            return false;
        }
        $a=explode(" | ",$rememberme);
        $res=$this->select(array(
            "selector_admin"=>$a[0]
        ));
        if(hash("sha384",$a[1]==$res[0]["verifier_rl"]))
        {
            return $res[0]["id_admin"];
        }else{
            return false;
        }
    }

    /**
     * supprime le cookie permettant à l'utilisateur d'etres connecté automatiquement sur le naviguateur actuelle
     *
     * @return bool true si le cookies était valide, false sinon
     */
    public function unsetRememberMe()
    {
        if($this->verifyRememberMe())
        {
            //suppression du cookie dans la base de donnée
            $rememberme=$_COOKIE["remembermeA"];
            $a=explode(" | ",$rememberme);
            $this->delete(array(
                "selector_admin"=>$a[0]
            ));
            //suppression du cookie dans le naviguateur
            //https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Proper_Deletion
            setcookie ("remembermeA", "", 1);
            setcookie ("remembermeA", false);
            unset($_COOKIE["remembermeA"]);
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param int $id de l'utilisateur dont nous voulons supprimer tous les cookies permettant un connection automatique sur tout les postes ou il était connecté
     *
     *permet de supprimer ton les token pour un utilisateur ( pour le deconnecter sur tout les appareil par exemple)
     */
    public function unsetAllRememberMe($id)
    {
        $this->delete(array(
            "id_admin"=>$id
        ));
    }
}