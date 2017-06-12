<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 13/06/17
 * Time: 10:15
 */

namespace App\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;

class ImportDataController extends Controllers
{
    public function postImportData(Request $request, Response $response)
    {
        $files = $request->getUploadedFiles();
        if (!empty($files['data'])) {
            echo "Fichier Récupérer</br>";
            $nom = $files['data']->getClientFilename();
            $nomtemporaire = $_FILES['data']['tmp_name'];
            $contenu = $files['data']->getStream();
            echo 'Nom temporaire: </br>' . $nomtemporaire . ' nom:' . $nom . '</br>';
            $extension_upload = strtolower(substr(strrchr($nom, '.'), 1));
            if ("csv" != $extension_upload) {
                echo "Type du fichier incorrrect</br>";
            } else {
                echo 'Extension Valide</br>';
                $handle = fopen($nomtemporaire, "r");
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $i++;
                    var_dump($data);
                    if ($data[0] == "Nom" || $data[0] == "JEUNESSE INTER SERVICES" || empty($data[0]) || $data[0] == "LA CRAU") {
                        echo 'Ligne non valide: '.$data[0].'</br>';
                    } else {
                        $dataRL = array(
                            "nom_rl" => $data[6],
                            "prenom_rl" => $data[7],
                            "adresse_mail_rl" => $data[14],
                        );
                        $date =date("Y-m-d", strtotime($data[3]));
                        $dataEnfant = array(
                            "nom_enfant" => $data[0],
                            "prenom_enfant" => $data[2],
                            "date_naissance_enfant" => $date
                        );
                        $RL = new \App\Models\Responsable_legal();
                        $enfant = new \App\Models\Enfant();
                        $infoRL = $RL->select($dataRL);
                        if ($infoRL[0]['id_responsable_legal'] == null) {
                            $RL->insert($dataRL);
                            $infoRL = $RL->select($dataRL);
                        }
                        $infoEnfant = $enfant->select($dataEnfant);
                        if ($infoEnfant[0]['id_enfant'] == null) {
                            $enfant->insert($dataEnfant);
                            $infoEnfant = $enfant->select($dataEnfant);
                            var_dump($infoEnfant);
                            $data_est_responsable_de = array(
                                "type_rl" => "Responsable Légal",
                                "id_enfant" => $infoEnfant[0]['id_enfant'],
                                "id_responsable_legal" => $infoRL[0]['id_responsable_legal']
                            );
                            $est_responsable_de = new \App\Models\Est_responsable_de();
                            $est_responsable_de->insert($data_est_responsable_de);
                        } else {
                            echo 'Enfant déja existant </br>';
                        }
                    }
                }
            }
        }
        //return $response->withRedirect($this->router->pathFor('importData.get'));
    }


    public function getImportData(Request $request, Response $response)
    {
        return $this->view->render($response, 'importData.twig');
    }
}