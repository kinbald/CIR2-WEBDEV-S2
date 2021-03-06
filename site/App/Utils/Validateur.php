<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 22/05/17
 * Time: 22:42
 *
 * inspiré de GUMP : https://github.com/Wixel/GUMP
 */

namespace App\Utils;


class Validateur
{
    public static function estValide($value,$type)
    {
        switch ($type)
        {
            case "integer":
                return is_int($value);
                break;
            case "mail" :
                return filter_var($value,FILTER_VALIDATE_EMAIL);
                break;
            case "string":
                return true;
            case "password":
                return Validateur::estValidePassword($value);
                break;
            case "datetime":
               // $d = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
               // return $d && $d->format('Y-m-d H:i:s') == $value;
                return true;
                break;
            case "date":
               // $d = \DateTime::createFromFormat('Y-m-d', $value);
                //return $d && $d->format('Y-m-d') == $value;
               //todo validateur de date
                return true;
                break;
            default:
                return false;
        }
    }

    /**
     * @param string $password mot de passe a tester
     * @return bool true si le mot de passe est assez solide, false si il est trop faible
     *
     * le mot de passe doit faire au moins 8 charactère,
     * il doit valider au moins deux des trois conditions suivante :
     * -majuscule
     * -minsucule
     * -chiffre
     */
    public static function estValidePassword($password){

        $count=0;
        $count+= preg_match('@[A-Z]@', $password);
        $count+= preg_match('@[a-z]@', $password);
        $count+= preg_match('@[0-9]@', $password);
        if($count>=2 && strlen($password) >= 8) {
            return true;
        }else{
            return false;
        }
    }

}