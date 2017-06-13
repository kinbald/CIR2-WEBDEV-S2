<?php
    /**
     * Created by PhpStorm.
     * User: kinbald
     * Date: 13/06/17
     * Time: 14:33
     */
    
    namespace App\Models;
    
    
    class Activite extends Models
    {
        protected $champs=array(
            "id_activite"=>"integer",
            "intitule"=>"string",
            "classname" => "string"
        );
        
        public function getIntitule($id_activite)
        {
            if($this->estExistant($id_activite))
            {
                return ($this->select(array("id_activite" => $id_activite))[0])['intitule'];
            }
        }
    
        public function getClassname($id_activite)
        {
            if($this->estExistant($id_activite))
            {
                return ($this->select(array("id_activite" => $id_activite))[0])['classname'];
            }
        }
    }