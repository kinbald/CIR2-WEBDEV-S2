<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 13/06/17
 * Time: 10:15
 */

namespace App\Controllers;


use App\Models\Classes;
use App\Models\Ecole;
use App\Models\Enfant;
use App\Models\Est_dans_classes;
use App\Models\Est_responsable_de;
use App\Models\Responsable_legal;
use Slim\Http\Request;
use Slim\Http\Response;

class ImportDataController extends Controllers
{
    public function postImportData(Request $request, Response $response)
    {
        $files = $request->getUploadedFiles();
        if (!empty($files['data'])) {
            //echo "Fichier Récupérer</br>";
            $nom = $files['data']->getClientFilename();
            $nomtemporaire = $_FILES['data']['tmp_name'];
            //echo 'Nom temporaire: </br>' . $nomtemporaire . ' nom:' . $nom . '</br>';
            $extension_upload = strtolower(substr(strrchr($nom, '.'), 1));
            if ("csv" != $extension_upload) {
                //echo "Type du fichier incorrrect</br>";
            } else {
                //echo 'Extension Valide</br>';
                $handle = fopen($nomtemporaire, "r");
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $i++;
                    var_dump($data);
                    if ($data[0] == "Nom" || $data[0] == "JEUNESSE INTER SERVICES" || empty($data[0]) || $data[0] == "LA CRAU" || $data[3] == "" || $data[5] == "") {
                        echo 'Ligne non valide: '.$data[0].'</br>';
                    } else {
                        $dataRL = array(
                            "nom_rl" => $data[6],
                            "prenom_rl" => $data[7],
                            "adresse_mail_rl" => $data[14],
                            "ville"=>$data[13],
                            "code_postal"=>$data[12],
                            "complement_d_adresse"=>$data[10].' '.$data[11]
                        );
                        //Conversion de la date
                        $date = str_replace('/', '-', strtotime($data[3]));
                        $date= date('Y-m-d', strtotime($date));
                        $dataEnfant = array(
                            "nom_enfant" => $data[0],
                            "prenom_enfant" => $data[2],
                            "date_naissance_enfant" => $date
                        );
                        /**
                         * Traitement de l'école
                         */
                        $dataEcole = array(
                            "nom_ecole"=>$data[5],
                        );
                        $ecole = new Ecole();
                        $infoEcole = $ecole->select($dataEcole);
                        if($infoEcole[0]['id_ecole']==null){
                            $ecole->insert($dataEcole);
                            $infoEcole=$ecole->select($dataEcole);
                        }
                        /**
                         * Traitement de la classe
                         */
                        $dataClasses =array(
                            "nom_classes"=>$data[4],
                            "id_ecole"=>$infoEcole[0]['id_ecole']
                        );
                        $classes = new Classes();
                        $infoClasses = $classes->select($dataClasses);
                        if($infoClasses[0]['id_classes']== null){
                            $classes->insert($dataClasses);
                            $infoClasses = $classes->select($dataClasses);
                        }
                        /**
                         * Traitement du Responsable Legal RL
                         */
                        $RL = new Responsable_legal();
                        $enfant = new Enfant();
                        $infoRL = $RL->select($dataRL);
                        if ($infoRL[0]['id_responsable_legal'] == null) {
                            $RL->insert($dataRL);
                            $infoRL = $RL->select($dataRL);
                        }
                        /**
                         * Traitement de l'enfant
                         */
                        $infoEnfant = $enfant->select($dataEnfant);
                        if ($infoEnfant[0]['id_enfant'] == null) {
                            $enfant->insert($dataEnfant);
                            $infoEnfant = $enfant->select($dataEnfant);
                            var_dump($infoEnfant);
                            //Ajout du responsable legal à l'enfant
                            $dataEst_responsable_de = array(
                                "type_rl" => "Responsable Légal",
                                "id_enfant" => $infoEnfant[0]['id_enfant'],
                                "id_responsable_legal" => $infoRL[0]['id_responsable_legal']
                            );
                            $est_responsable_de = new Est_responsable_de();
                            $est_responsable_de->insert($dataEst_responsable_de);
                            //Ajout de la classe à l'enfant
                            $dataEst_dans_classes=array(
                                "id_classes"=>$infoClasses[0]['id_classes'],
                                "id_enfant"=>$infoEnfant[0]['id_enfant'],
                            );
                            $est_dans_classes = new Est_dans_classes();
                            $est_dans_classes->insert($dataEst_dans_classes);
                        }
                    }
                }
            }
        }
    }


    public function getImportData(Request $request, Response $response)
    {
        return $this->view->render($response, 'importData.twig');
    }
}