<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 25/05/17
 * Time: 18:08
 */

namespace App\Middleware;


use Psr\Container\ContainerInterface;

/**
 * Class Middleware
 * @package App\Middleware
 */
class Middleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Middleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Permet d'éviter de devoir faire $this->container
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container->$name;
    }
}