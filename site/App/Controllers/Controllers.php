<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 26/04/17
 * Time: 19:27
 */

namespace App\Controllers;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

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
     * Controllers constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
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