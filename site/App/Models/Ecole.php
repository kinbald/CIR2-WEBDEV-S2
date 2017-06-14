<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 09:28
 */

namespace App\Models;


class Ecole extends Models
{
    protected $champs=array(
        "id_ecole"=>"integer",
        "nom_ecole"=>"string"
    );
}