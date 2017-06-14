<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 09:37
 */

namespace App\Models;


class Est_dans_classes extends Models
{
    protected $champs=array(
        "id_classes"=>"integer",
        "id_enfant"=>"integer",
        "type_inscription"=>"string"
    );
}