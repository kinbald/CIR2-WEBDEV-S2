<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 04/06/17
 * Time: 21:38
 */

namespace App\Models;


class est_responsable_de extends Models
{

    protected $champs = array(
        "type_rl" => "string",
        "id_enfant" => "integer",
        "id_responsable_legal" => "integer"
    );

    /**
     * @param int $id du responsable legal
     * @return array contenant les id des enfant et le type de responsabilité
     */
    public function enfant_depuis_id_rl($id)
    {
        return $this->select(array("id_responsable_legal" => $id));
    }

    public function id_enfant_depuis_id_rl($id)
    {
        $enfants = array();
        foreach ($this->enfant_depuis_id_rl($id) as $enfant) {

            $enfants[] = $enfant['id_enfant'];
        }
        return $enfants;
    }
    
    public function estReponsable($id_parent, $id_enfant)
    {
        $enfants = $this->select(array('id_parent' => $id_parent));
        foreach ($enfants as $enfant) {
            if($enfant['id_enfant'] == $id_enfant)
            {
                return true;
            }
        }
        return false;
    }
}