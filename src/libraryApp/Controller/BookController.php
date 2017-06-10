<?php
/**
 * Created by PhpStorm.
 * User: n.braveen
 * Date: 02.06.17
 * Time: 16:04
 */
namespace libraryApp\Controller;
use framework;

/*
 * In this class BookController are contained all the actions
 * of the project related to the library function.
 */
class BookController
{

    public function listAction()
    {
        $registry = framework\Registry::getInstance();
        $conn = $registry->getParam('db');

        $sql = " SELECT ean, title, author, updated_date FROM libraryBook";
        $results = $conn->prepare($sql);
        $results->execute();

        if ($results->rowCount() > 0) {
            echo '
        <h1>Book list: </h1>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author Name</th>
                    <th>EAN</th>
                    <th>Updated time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';

            $rows = $results->fetchAll();
            foreach ($rows as $row) {
                echo '
                    <tr>
                        <td>' . $row["title"] . '</td>
                        <td>' . $row["author"] . '</td>
                        <td>' . $row["ean"] . '</td>
                        <td>' . $row["updated_date"] . '</td>
                        <td>
                            <button type="button" class="btn btn-primary" href="?action=detail&?id='. $row['ean'] . '">View</button>
                            <button type="button" class="btn btn-warning" href="?action=edit">Edit</button>
                            <button type="button" class="btn btn-danger" href="?action=delete">Delete</button>
                        </td>
                    </tr>';

            }
            echo '  </tbody>
            </table>';
        } else {
            echo '<b> No data found.</b>';
        }
    }

    public function addAction()
    {
        $this->createForm();

        $book = $this->gettingBook();

        $errorMessage = $this->validation($book);

        if (empty($errorMessage) === true) {

            $this->insertBook($book);

        }
    }

    public function createForm()
    {
        echo '

    <form method="post" action="#">
        <div class="form-group">
            <label>Book Title</label>
            <input name="title"type="text" class="form-control" placeholder="Enter the book title">
        </div>
        <div class="form-group">
            <label>Book Author</label>
            <input name="author" type="text" class="form-control"  placeholder="Enter the book author">
        </div>
        <div class="form-group">
            <label>Book EAN</label>
            <input name="ean" type="text" class="form-control"  placeholder="Enter the book EAN">
            <small class="form-text text-muted">You can find it over the barcode of the book</small>
        </div>
            <button name="submit" value="send" type="submit" class="btn btn-primary">Submit</button>
    </form>

';
    }

    public function gettingBook()
    {
        $book = null;

        if (!empty($_POST['submit']))
        {
            $book = array(
                'title' => $_POST['title'],

                'author' => $_POST['author'],

                'ean' => $_POST['ean'],

                'datetime' => date("Y-m-d H:i:s")
            );

        }

    return $book;

    }

    public function validation($book)

    {
        if (empty($book) == false) {

            $errorMessage = null;

            if (is_array($book) == true) {
                foreach ($book as $key => $value) {

                    if (empty($book[$key]) == true) {

                        $errorMessage .= '<br /> The <b>' . $key . '</b> is empty.';
                    }
                }
            }

            if (is_numeric($book['ean']) == false) {

                $errorMessage .= '<br /> The <b>ean</b> should be an integer';

            }
            if (empty($errorMessage) == false) {
                echo '<b>ERROR: </b>';
            }

        } else {
            $errorMessage = '<b>Please enter the details </b>';
        }

        echo $errorMessage;

        return $errorMessage;


    }

    public function insertBook ($book)
    {
        $registry = framework\Registry::getInstance();
        $conn = $registry->getParam('db');

            try {

                $sql = " INSERT INTO libraryBook (ean, title, author, updated_date)
                    VALUES ('" . $book['ean'] ."','" . $book['title'] . "', '" . $book['author'] . "', '". $book['datetime'] ."')";

                $conn->exec($sql);


            }
            catch (PDOException $e) {

                echo "Wait!!! :( Connection failed: " . $e->getMessage();

            }


    }
}

?>