<?php

/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 27/05/17
 * Time: 11:31
 */

namespace App\Utils;

/**
 * Class Utils
 * @package App\Utils
 */
class Utils
{
    /**
     * Fonction permettant de générer un mot de passe aléatoirement
     * @return string
     */
    static function generatePassword()
    {
        return substr(str_shuffle(str_repeat(SEED, PASSWORD_SIZE)), 0, PASSWORD_SIZE);
    }
}