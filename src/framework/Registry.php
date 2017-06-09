<?php
/**
 * Created by PhpStorm.
 * User: n.braveen
 * Date: 04.06.17
 * Time: 21:07
 */

namespace framework;

/*
 * The Registry class is singleton pattern.
 * This is useful to have exactly one instance of an object to be
 * exchanged within the classes of the project.
 * This allows the elimination of the global variables.
 */
class Registry
{
    /*
     * The private params is an array holding all the parameters
     * we want to save in the registry.
     */
    private $params = [];

    private static $instance = null;

    /*
     * Create the instance if not created already
     * If already created it returns the $instance
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            //echo 'created new';
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
    }

    /*
     * Adds the parameters to the array params
     * the params have a key and a value.
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }
    /*
     * Get the param value by entering the key
     */
    public function getParam($key)
    {
        return $this->params[$key];
    }

}