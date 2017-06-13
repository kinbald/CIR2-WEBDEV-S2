<?php
    /**
     * Created by IntelliJ IDEA.
     * User: Kinbald
     * Date: 12/06/17
     * Time: 09:44
     */
    
    namespace App\Models;

    /**
     * Class Creneau
     * @package App\Models
     */
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
         * @return bool|\PDOStatement
         */
        public function ajouteCreneauEnfant($id_enfant, $date_journee, $id_activite)
        {
            if($this->verifieDateCoherente($date_journee))
            {
                if( (new Enfant())->estExistant($id_enfant) )
                {
                    return $this->insert(array(
                        "id_enfant" => $id_enfant,
                        "date_journee" => $date_journee,
                        "id_activite" => $id_activite
                    ));
                }
            }
        }
        
        /**
         * Fonction permettant de vérifier si l'heure du créneau à ajouter est cohérente par rapport au modèle du CDC
         * @param $dateValidation
         * @return bool
         */
        public function verifieDateCoherente($dateValidation)
        {
            $dateAValider = new \DateTime($dateValidation);
            $dateCourante = new \DateTime();
            if($dateAValider >= $dateCourante)
            {
                if($dateCourante->format('N') === 1 && $dateCourante->format('H') < 11)
                {
                    return true;
                }
                else
                {
                    if ($dateAValider >= date_create(date('Y-m-d H:i:s', strtotime('next Monday'))) )
                    {
                        return true;
                    }
                }
            }
            return false;
        }
        
        /**
         * Fonction permettant de supprimer un créneau pour un enfant à une date et une activite précise
         * @param $id_enfant
         * @param $date_journee
         * @param $id_activite
         */
        public function supprimerCreneau($id_enfant, $date_journee, $id_activite)
        {
            if($this->verifieDateCoherente($date_journee))
            {
                if( $this->estExistant($id_enfant, array("id_activite" => $id_activite, "date_journee" => $date_journee)) )
                {
                    $this->delete(array("id_enfant" => $id_enfant, "date_journee" => $date_journee, "id_activite" => $id_activite));
                }
            }
        }
    
        /**
         * Vérifie si l'élément correspondant à l'id est existant dans la base de données
         * @param $id
         * @param null $params
         * @return bool
         */
        public function estExistant($id, $params = null)
        {
            $selectParams = array(
                "id_enfant" => $id,
                "id_activite" => $params["id_activite"],
                "date_journee" => $params["date_journee"]
            );
            return !empty($this->select($selectParams));
        }
    
        /**
         * @param $NumAnnee
         * @param $NumMois
         * @param $Enfant
         * @return array
         */
        public function getCreneauxMois($NumAnnee, $NumMois, $Enfant)
        {
            if( (new Enfant())->estExistant($Enfant) )
            {
                $sql = "SELECT date_journee, id_activite FROM creneau WHERE id_enfant = $Enfant AND EXTRACT(MONTH FROM date_journee) = $NumMois AND EXTRACT(YEAR FROM date_journee) = $NumAnnee";
                $req = $this->execute($sql);
                return $req->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
    }