<?php
/**
 * Created by PhpStorm.
 * User: n.braveen
 * Date: 04.06.17
 * Time: 20:07
 */

namespace libraryApp\Service\Db;
use PDO;

//include 'App/config/databaseAccess.php';
class Connection
{
    static public function connection ()
    {

        $mysql_servername = "localhost";
        $mysql_username   = "root";
        $mysql_password   = "";
        $myDB             = "librarydb";

        try {
            $conn = new PDO("mysql:host=$mysql_servername;dbname=$myDB", $mysql_username, $mysql_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo '
                    
                    <span id="DbSignal" class="glyphicon glyphicon-signal" aria-hidden="true"> connected</span>
                    ';

            return $conn;

        } catch (PDOException $error) {

            echo '<div class="alert alert-danger" role="alert">
                  <strong>You are not connected</strong> to the database. <br /> <strong>See Error: </strong>' . $error->getMessage() . '</div>';
        }
    }
}
