<?php
/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 28/05/17
 * Time: 13:37
 */

namespace App\Models;

/**
 * Class Enfant
 * @package App\Models
 */
class Enfant extends Models
{
    protected $champs=array(
        "id_enfant"=>"integer",
        "nom_enfant"=>"string",
        "prenom_enfant"=>"string",
        "date_naissance_enfant"=>"date"
    );

    /**
     * Fonction qui retourne le prénom de l'enfant associé à l'indentifiant
     * @param int $id_enfant
     * @return string
     */
    public function getPrenom($id_enfant)
    {
        $child = ($this->select(array("id_enfant" => $id_enfant)))[0];
        return $child['prenom_enfant'];
    }
    public function recupèreIdEnfant($data)
    {
        $res = $this->select($data);
        return $res[0];
    }

}