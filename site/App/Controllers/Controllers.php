<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 26/04/17
 * Time: 19:27
 */

namespace App\Controllers;
use App\Session;
use Psr\Container\ContainerInterface;

/**
 * Class Controllers
 * @package App\Controllers
 */
class Controllers
{
    /**
     * @var ContainerInterface
     *
     * variable permettant de stocker le container, et tout ce à quoi il permet d'accéder
     */
    protected $container;

    /**
     * @var Session Instance de session
     */
    protected $sessionInstance;

    /**
     * Controllers constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->sessionInstance = Session::getInstance();
    }

    /**
     * @param $name
     * @return mixed
     *
     * permet d'eviter de devoir faire $this->container
     */
    public function __get($name)
    {
        return $this->container->$name;
    }
}