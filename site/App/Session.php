<?php

/**
 * Created by IntelliJ IDEA.
 * User: kinbald
 * Date: 27/05/17
 * Time: 12:03
 */

namespace App;

/**
 * Class Session
 * @package App
 */
class Session
{
    /**
     * @var Session Instance de Session
     */
    static $instance;

    /**
     * Permet de récupérer une instance la session
     * @return Session
     */
    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    public function write($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function read($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

}