<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
 /*Autoloader includes the files of unknown called classes. */

    spl_autoload_register(function ($className) {
        $filename = "src/" . str_replace("\\", '/', $className) . ".php";
        if (file_exists($filename)) {
            //echo $filename;
            include($filename);
            if (class_exists($className)) {
                return true;
            }
        }
        return false;
    });

?>

<!DOCTYPE html>
<html>

<head>
    <title> LibraryApp </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="/libraryapp/web/css/style.css" rel="stylesheet">
</head>

<body>

<img class="bkgpicture" src="/libraryapp/web/images/librarypicture.jpg"/>

<header>
    <?php
    //calls the connection static function
    //save the object conn in the singleton function Registry
    //so we can use it in other functions aswell.

    $conn = \libraryApp\Service\Db\Connection::connection();
    $registry = framework\Registry::getInstance();
    $registry->addParam('db', $conn);

    include ('/Users/n.braveen/Sites/libraryapp/view/header.php');
    ?>

</header>

<div id="content">
    <?php

    //GET controller, action, id from URL


    if (!empty ($_GET['controller']))
    {
        $controller = $_GET['controller'];


        if(!empty($_GET['action']))
        {
            $action = $_GET ['action'];

            if(!empty($_GET['id']))

            {
                $id = $_GET ['id'];

            } else {

                $id = null;
            }

        } else {

            $action = null;
            $id = null;
        }
    } else {

        $controller = null;
        $action = null;
        $id = null;
    }




    //Calls router to get the exact controller name and action name

    $route = new \framework\Router($controller, $action);
    $controller = $route->getController();
    $action = $route->getAction();

    //Calls the dispatch to call the actions in the controller.

    $dispatch = new \framework\Dispatcher($controller, $action, $id);
    $dispatch->dispatch();
    ?>
</div>

<footer>
    <?php
    include ("view/footer.php")
    ?>
</footer>
</body>

</html>
