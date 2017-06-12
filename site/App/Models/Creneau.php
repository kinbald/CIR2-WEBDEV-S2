<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 12/06/17
 * Time: 09:44
 */

namespace App\Models;


class Creneau extends Models
{
    protected $champs=array(
        "id_enfant"=>"integer",
        "id_activite"=>"integer",
        "date_journee"=>"date",
    );

    /**
     * Fonction permettant d'ajouter un créneau pour un enfant
     * @param $id_enfant
     * @param $date_journee
     * @param $id_activite
     */
    public function ajouteCreneauEnfant($id_enfant, $date_journee, $id_activite)
    {
        if( (new Enfant())->estExistant($id_enfant) )
        {
            $this->insert(array(
                "id_enfant" => $id_enfant,
                "date_journee" => $date_journee,
                "id_activite" => $id_activite
            ));
        }
    }

    public function verifieDateCoherente()
    {
        //TODO Fonction
    }

    /**
     * Fonction permettant de supprimer un créneau pour un enfant à une date et une activite précise
     * @param $id_enfant
     * @param $date_journee
     * @param $id_activite
     */
    public function supprimerCreneau($id_enfant, $date_journee, $id_activite)
    {
        if( $this->estExistant($id_enfant, array("id_activite" => $id_activite, "date_journee" => $date_journee)) )
        {
            $this->delete(array("id_enfant" => $id_enfant, "date_journee" => $date_journee, "id_activite" => $id_activite));
        }
    }

    public function estExistant($id, $params = null)
    {
        $selectParams = array(
            "id_enfant" => $id,
            "id_activite" => $params["id_activite"],
            "date_journee" => $params["date_journee"]
        );
        return !empty($this->select($selectParams));
    }


}