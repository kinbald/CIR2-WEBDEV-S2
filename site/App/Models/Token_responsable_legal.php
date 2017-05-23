<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 23/05/17
 * Time: 16:46
 *
 * https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Remember_Me
 */

namespace App\Models;


use Swift_IoException;
use Swift_Message;

class Token_responsable_legal extends Models
{

    protected $champs = array(
        "verifier_rl" => "string",
        "selector_rl" => "string",
        "id_responsable_legal" => "integer",
        "date_expiration_rl" => "string",
    );

    /**
     * @param int $id de l'utilisateur dont nous devons nous souvenir
     *
     * permet de créer un cookie pour se souvenir de l'utilisateur, et l'inserer en memoire.
     */
    public function setRememberMe($id)
    {
        $selector = bin2hex(random_bytes(250));
        $validator = bin2hex(random_bytes(250));
        $hash_c = hash("sha384", $validator);
        $dateExpiration = time() + 24 * 60 * 60 * 10;
        setcookie("rememberme", $selector . " | " . $validator, $dateExpiration, null, null, null, true);
        $this->insert(array(
            "verifier_rl" => $hash_c,
            "selector_rl" => $selector,
            "date_expiration_rl" => date("Y/m/d", $dateExpiration),
            "id_responsable_legal" => $id,
        ));
        var_dump(date("d/m/Y", $dateExpiration));
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
        $rememberme = $_COOKIE["rememberme"];
        $a = explode(" | ", $rememberme);
        $res = $this->select(array(
            "selector_rl" => $a[0]
        ));
        if (hash("sha384", $a[1] == $res[0]["verifier_rl"])) {
            return $res[0]["id_responsable_legal"];
        } else {
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
        if ($this->verifyRememberMe()) {
            //suppression du cookie dans la base de donnée
            $rememberme = $_COOKIE["rememberme"];
            $a = explode(" | ", $rememberme);
            $this->delete(array(
                "selector_rl" => $a[0]
            ));
            //suppression du cookie dans le naviguateur
            //https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#Proper_Deletion
            setcookie("rememberme", "", 1);
            setcookie("rememberme", false);
            unset($_COOKIE["rememberme"]);
            return true;
        } else {
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
            "id_responsable_legal" => $id
        ));
    }

    public function setTokenRecovery($email)
    {
        $info = (new Responsable_legal())->select(array("adresse_mail_rl" => $email));
        if ($info[0]["id_responsable_legal"] > 0) {

            //generation du token
            $token = bin2hex(random_bytes(50));

            //ajout du token dans la base de données
            $this->insert(array(
                "selector_rl" => "recover",
                "verifier_rl" => $token,
                "date_expiration_rl" => (new \DateTime())->add(new \DateInterval('P1D'))->format("Y/m/d"),
                "id_responsable_legal" => $info[0]["id_responsable_legal"],
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
                   <a href=\"127.0.0.1/recover/" . $token . "\">127.0.0.1/recover/" . $token . "</a> <br></div>")
                    //header necessaire pour pouvoir cliquer sur le lien
                    ->setContentType("text/html; charset=\"UTF-8\"");
                $this->container->mailer->send($message);
            } catch (Swift_IoException $e) {
                echo $e;
            }
        } else {
            return false;
        }
    }
}