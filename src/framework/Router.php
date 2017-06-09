<?php
/**
 * Created by PhpStorm.
 * User: n.braveen
 * Date: 04.06.17
 * Time: 14:46
 */

namespace framework;


class Router
{
    private $controller = null;
    private $action = null;

    //Construct the local variables
    public function __construct($controller = null, $action = null)
    {
     $this->controller = $controller;
     $this->action = $action;
    }

    /*
     * Checks if the value $controller is not empty/null
     * If empty returns default value
     * If not proceed with formatting
     * */

    public function getController ()
    {
        $controller = $this->controller;

        if(!empty($controller))
        {
            $controllerName = $this->formatControllerName($controller);
        } else {
            $controllerName = "Default";
        }

        return $controllerName;

    }
    /*
     * Checks if the value $controller is not empty/null
     * If empty returns default value
     * If not proceed with formatting
     * */

    public function getAction ()
    {
        $action = $this->action;

        if(!empty($action))
        {
            $actionName = $this->formatActionName($action);

        } else {
            $actionName = "default";
        }

        return $actionName;
    }

    /*
     * Formats the controller name by calling formatString
     */
    private function formatControllerName ($controller)
    {
        $controllerName = $this->formatString($controller);
        return $controllerName;
    }
    /*
     * Formats the action name by calling formatString;
     * Then it adds an ulterior change with lcfirst function
     * It changes the first character in a lowercase
     * (ex. "ListAction" in "listAction")
     */
    private function formatActionName ($action)
    {
        $actionName = $this->formatString($action);

        $actionName = lcfirst($actionName);

        return $actionName;
    }
    /*
     * Format the name in three steps:
     * 1) Replace - with " " (ex. "book-controller" in "book controller");
     * 2) With ucwords it makes each initial character of the word Uppercase
     *    (ex. "book controller" in "Book Controller");
     * 3) Replace the " " with "" (ex. "Book Controller" in "BookController");
     */
    private function formatString ($string)
    {
        $formatted = str_replace('-'," ",$string);
        $formatted = ucwords($formatted);
        $formatted = str_replace(" ", "",$formatted);

        return $formatted;
    }

}