<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 23/05/17
 * Time: 19:03
 */

namespace App\Models;

use Swift_IoException;
use Swift_Message;

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

    /**
     * @param string $email de l'utilisateur voulant reinitialiser son mot de passe
     * @return bool false si l'email n'existe pas dans la base de donnée
     * @return true si l'email existe dans la base de donnée
     */
    public function setTokenRecovery($email)
    {
        $info = (new Admin())->select(array("adresse_mail" => $email));
        if ($info[0]["id_admin"] > 0) {

            //generation du token
            $token = bin2hex(random_bytes(20));

            //ajout du token dans la base de données
            $this->insert(array(
                "selector_admin" => "recover-admin",
                "verifier_admin" => $token,
                "date_expiration_admin" => (new \DateTime())->add(new \DateInterval('P1D'))->format("Y/m/d"),
                "id_admin" => $info[0]["id_admin"],
            ));

            //envoie du mél
            try {
                $message = Swift_Message::newInstance()
                    //emetteur
                    ->setFrom(array('testleasen@gmail.com' => 'leasen'))
                    //destinataire
                    ->setTo($email)
                    //sujet
                    ->setSubject('Mot de passe oublié')
                    //corp du text
                    ->setBody("<div> Voici le lien sur lequel vous devez cliquer : 
                   <a href=\"127.0.0.1/recover-admin/" . $token . "\">127.0.0.1/recover-admin/" . $token . "</a> <br></div>")
                    //header necessaire pour pouvoir cliquer sur le lien
                    ->setContentType("text/html; charset=\"UTF-8\"");
                $this->container->mailer->send($message);
            } catch (Swift_IoException $e) {
                echo $e;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * @param string $token permet de verifier si un token permettant de reinitialiser son mot de passe exste dans la base de donnée
     * @return bool|int false si le token n'existe pas, l'id de l'utilisateur si il existe
     */
    public function existeTokenRecover($token)
    {
        $args=array(
            "selector_admin"=>"recover-admin",
            "verifier_admin"=>$token,
        );
        $res=$this->select($args);
        if($res[0]["id_admin"]>0)
        {
            return $res[0]["id_admin"];
        }else{
            return false;
        }
    }
}