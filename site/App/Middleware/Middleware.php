<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kinbald
 * Date: 25/05/17
 * Time: 18:08
 */

namespace App\Middleware;


use App\Session;
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
     * @var Session Instance de session
     */
    protected $sessionInstance;

    /**
     * Middleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->sessionInstance = Session::getInstance();
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