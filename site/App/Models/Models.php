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


class Models
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
        $this->pdo=$container->pdo;
        $this->container=$container;
    }

    /**
     * @param String $sql
     * @return \PDOStatement
     */
    protected function execute(string $sql)
    {
        $req=$this->pdo->prepare($sql);
        try {
            $req->execute();
        } catch (PDOException $e) {
            if ($this->container->get('settings')["debug"]>0) {
                echo $e->getMessage();
            } else {
                echo 'bdd indispo';
            }
        }
        return $req;

    }

    /**
     * @param $data
     * @param string $order
     * @param int $limit
     * @return array
     */
    public function insert($data,$order="",$limit=0){
        $sql='SELECT * FROM '.get_class($this);
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
        if($limit>0)
        {
            $sql.=" LIMIT ".$limit;
        }

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}