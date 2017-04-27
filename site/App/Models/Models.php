<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 27/04/17
 * Time: 17:21
 */

namespace App\Models;
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
}