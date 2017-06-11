<?php
/**
 * Created by PhpStorm.
 * User: n.braveen
 * Date: 04.06.17
 * Time: 16:14
 */

namespace framework;

class Dispatcher
{

    public function __construct($controller = null, $action = null, $id = null)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->id = $id;
    }

    /*
     * This function calls controls the controller name and action name by calling the
     * functions controllerVerify and actionVerify
     * The if the controls are returned true, it calls the action in the controller.
     * If the controller or action does not exists, it includes the homepage.
     */
    public function dispatch ()
    {
        $controllerName = $this->controller;
        $actionName = $this->action;
        $idValue = $this->id;

        $validationController = $this->controllerVerify($controllerName);
        $validationAction = $this->actionVerify($controllerName, $actionName);

        if ($validationController == 1) {

            if ($validationAction == 1) {

                $classControllerName = 'libraryApp\\Controller\\' . $controllerName;
                $controller = new $classControllerName;

                $controller->$actionName($idValue);

            } else {
                //echo "action does not exists";
                include "view/homepage.php";
            }

        } else {
            //echo "Controller does not exists";
            include "view/homepage.php";
        }

        return true;
    }
    /*
     * controllerVerify checks if the controller file exists.
     * It returns a bool
     */

    private function controllerVerify ($controller)
    {
        $controllerPath = 'src/libraryApp/Controller/' . $controller . '.php';
        $checkController = file_exists($controllerPath);

        return $checkController;
    }
    /*
     * actionVerify checks if the in the class/file controller the action exists
     * with the method_exists function.
     * It returns a bool.
     */
    private function actionVerify ($controller, $action)
    {
        $classControllerName = 'libraryApp\\Controller\\' . $controller;
        //$controller = new $classControllerName;

        $existsAction = method_exists($classControllerName, $action);
        //echo '<br /> The result: ' . $existsAction . '<br />';

        return $existsAction;
    }



}