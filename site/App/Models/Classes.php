<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/06/17
 * Time: 09:44
 */

namespace App\Models;


class Classes extends Models
{
    protected $champs=array(
        "id_classes"=>"integer",
        "nom_classes"=>"string",
        "annee"=>"date",
        "enseignant"=>"string",
        "id_ecole"=>"integer"
    );

}