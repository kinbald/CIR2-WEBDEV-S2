<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 22/05/17
 * Time: 22:42
 *
 * inspiré de GUMP : https://github.com/Wixel/GUMP
 */

namespace App\Models;


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

            default:
                return false;
        }
    }


}