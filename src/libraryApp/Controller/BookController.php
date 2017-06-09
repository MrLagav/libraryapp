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
                            <button type="button" class="btn btn-primary" href="?action=view">View</button>
                            <button type="button" class="btn btn-warning">Edit</button>
                            <button type="button" class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
          ';
            }
            echo '</tbody>
        </table>';
        } else {
            echo 'no data found.';
        }
    }

    public function addAction()
    {
        $errorMessage = null;

        $this->createForm();
        $book = $this->gettingBook();

        if ($book != null) {

            $errorMessage = $this->validation($book);

            if ($errorMessage = null) {
                $this->insertBook($book);

            }
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

                'ean' => $_POST['ean']
            );

        }
    return $book;

    }

    public function validation($book)


    {
        $errorMessage = null;

        if ( is_array($book) == true ) {
            foreach ($book as $key => $value) {

                if (empty($book[$key]) == true) {

                    $errorMessage .= '<br /> The <b>' . $key . '</b> is empty.';
                }

            }
        }

        if (is_numeric($book['ean']) == false) {

            $errorMessage .= '<br /> The <b>ean</b> should be an integer';

        }
        if ($errorMessage!=null)
        {
            echo '<b>ERROR: </b>' . $errorMessage . '<br /> <b> Fill with the correct information </b>';
        }

        return $errorMessage;


    }

    public function insertBook ($book)
    {

            try {

                foreach ($book as $key->value)

                $sql = " INSERT INTO libraryBook (ean, title, author, updated_date)
                    VALUES ('" . $book['ean'] ."','" . $book['title'] . "', '" . $book['author'] . "', DATETIME;

                $conn->exec($sql);
                header("Location:execute.php");
            }
            catch (PDOException $e) {
                echo "Wait!!! :( Connection failed: " . $e->getMessage();
            }




    }
}

?>