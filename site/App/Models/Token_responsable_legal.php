<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 23/05/17
 * Time: 16:46
 */

namespace App\Models;


class Token_responsable_legal extends Models
{

    protected $champs =array(
        "verifier_rl"=>"string",
        "selector_rl"=>"string",
        "id_responsable_legal"=>"integer",
        "date_expiration_rl"=>"string",
    );

    /**
     * @param int $id de l'utilisateur dont nous devons nous souvenir
     *
     * permet de créer un cookie pour se souvenir de l'utilisateur, et l'inserer en memoire.
     */
    public function setRememberMe($id)
    {
        $selector=utf8_encode(random_bytes(250));
        $validator=utf8_encode(random_bytes(250));
        $hash_c=hash("sha384",$validator);
        $dateExpiration=time()+24*60*60*10;
        setcookie("rememberme",$selector." | ".$validator,$dateExpiration,null,null,null,true);
        var_dump($this->insert(array(
            "verifier_rl"=>$hash_c,
            "selector_rl"=>$selector,
            "date_expiration_rl"=> date("d/m/Y",$dateExpiration),
            "id_responsable_legal"=>$id,
        )));
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
        $rememberme=$_COOKIE["rememberme"];
        $a=explode(" | ",$rememberme);
        $res=$this->select(array(
            "selector_rl"=>$a[0]
        ));
        if(hash("sha384",$a[1]==$res[0]["verifier_rl"]))
        {
            return $res[0]["id_responsable_legal"];
        }else{
            return false;
        }
    }
}