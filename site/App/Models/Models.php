<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 27/04/17
 * Time: 17:21
 */

namespace App\Models;

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
     * Models constructor.
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container)
    {
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
        $sql = 'SELECT * FROM ' . get_class($this);
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
        // echo $sql.'<br>';
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
     * @return \PDOStatement
     *
     * les valeurs sont quoté avant d'être insérée, pas les nom des colonne
     */
    public function insert($data)
    {
        // (new \ReflectionClass($this))->getShortName() permet d'obtenir le nom sans le namespace
        $sql = 'INSERT INTO ' . (new \ReflectionClass($this))->getShortName();
        foreach ($data as $k => $v) {
            $data[$k] = $this->pdo->quote($v);
        }
        // implode keys of $data...
        $sql .= " (" . implode("`, `", array_keys($data)) . ")";

        // implode values of $data
        $sql .= " VALUES (" . implode("', '", $data) . ") ";
        //execute la commande
        return $this->execute($sql);
    }
}