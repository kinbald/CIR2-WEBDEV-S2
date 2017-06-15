<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 27/04/17
 * Time: 17:21
 */

namespace App\Models;

use App\Utils\Validateur;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;


abstract class Models
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var array contenant les noms des champs et le type sous la formes nom_champ=>type;
     *
     */
    protected $champs;

    /**
     * @var ContainerInterface permet de stocker le dernier container utiliser;
     */
    public static $Scontainer;

    /**
     * Models constructor.
     */
    function __construct()
    {

        $container = Models::$Scontainer;
        $this->pdo = $container->pdo;
        $this->container = $container;
    }

    /**
     * @param String $sql
     * @return \PDOStatement
     */
    protected function execute(string $sql)
    {
        $req = $this->pdo->prepare($sql);
        if ($this->container->get('settings')["debug"] > 1) {
            var_dump($sql);
        }
        try {
            $req->execute();
        } catch (PDOException $e) {
            if ($this->container->get('settings')["debug"] > 0) {
                echo $e->getMessage();
            } else {
                echo 'bdd indispo';
            }
        }
        return $req;

    }

    /**
     * @param mixed $data : string à ajouter après SELECT * FROM nom-classes WHERE ou array sous la forme "nom_colonne"=> valeur qui deviendras nom_colonne = valeur
     * @param string $order
     * @param int $limit
     * @return array
     */
    public function select($data, $order = "", $limit = 0)
    {
        $sql = 'SELECT * FROM ' . (new \ReflectionClass($this))->getShortName();
        $a_cond = array();
        if (isset($data)) {
            $sql .= ' WHERE ';
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    //if (!is_numeric($v)) {
                    $v = $this->pdo->quote($v);
                    //}
                    $a_cond[] = "$k = $v";
                }
                $sql .= implode(' AND ', $a_cond);

            } else {
                $sql .= $data;
            }
        }
        //echo $sql.'<br>';
        if ($order != "") {
            $sql .= " ORDER BY " . $order;
        }
        if ($limit > 0) {
            $sql .= " LIMIT " . $limit;
        }

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data tableau contenant les valeurs sous la formes "nom_colonne"=>"valeurs"
     * @return bool|\PDOStatement
     *
     * les valeurs sont quoté avant d'être insérée, pas les nom des colonne
     */
    public function insert($data)
    {
        // (new \ReflectionClass($this))->getShortName() permet d'obtenir le nom sans le namespace
        $sql = 'INSERT INTO ' . (new \ReflectionClass($this))->getShortName();
        foreach ($data as $k => $v) {
            //verifie que le champs correspond au type attend et qu'il existe dans la table
            if (!Validateur::estValide($v, $this->champs[$k])) {
                echo $v;
                return false;
            }
            $data[$k] = $this->pdo->quote($v);
        }
        // implode keys of $data...
        $sql .= " (" . implode(",", array_keys($data)) . ")";

        // implode values of $data
        $sql .= " VALUES (" . implode(",", $data) . ") ";
        //execute la commande
        return $this->execute($sql);
    }

    /**
     * @param $data : string à ajouter après DELETE FROM nom-classes WHERE ou array sous la forme "nom_colonne"=> valeur qui deviendras nom_colonne = valeur
     * @return \PDOStatement
     *
     * permet de supprimer les colonne du tables
     */
    protected function delete($data)
    {
        $sql = 'DELETE FROM ' . (new \ReflectionClass($this))->getShortName();
        $a_cond = array();
        if (isset($data)) {
            $sql .= ' WHERE ';
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    //if (!is_numeric($v)) {
                    $v = $this->pdo->quote($v);
                    //}
                    $a_cond[] = "$k = $v";
                }
                $sql .= implode(' AND ', $a_cond);

            } else {
                $sql .= $data;
            }
        }
        return $this->execute($sql);
    }

    public function update($data,$cond)
    {
        $sql=' UPDATE '.(new \ReflectionClass($this))->getShortName().' SET ';
        $a_cond = array();
        foreach ($data as $k => $v) {
            //if (!is_numeric($v)) {
            $v = $this->pdo->quote($v);
            //}
            $a_cond[] = "$k = $v";
        }
        $sql .= implode(',', $a_cond);
        $sql.=" WHERE ".$cond;
        return $this->execute($sql);
    }

    public function estExistant($id, $params = null)
    {
        if( empty($this->select(array(key($this->champs) => $id))) )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}