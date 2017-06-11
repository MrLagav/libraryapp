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
                            <a class="btn btn-primary" href="?controller=bookcontroller&action=viewAction&id='. $row["ean"] . '">View</a>
                            <a class="btn btn-warning" href="?controller=bookcontroller&action=editAction&id='. $row["ean"] . '">Edit</a>
                            <a class="btn btn-danger" href="?action=delete">Delete</a>
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
        $book = null;

        $this->createForm($book);

        $book = $this->gettingBook();

        $errorMessage = $this->validation($book);

        if (empty($errorMessage) === true) {

            $this->insertBook($book);
            header('Location: index.php?controller=bookcontroller&action=listaction');
        }
    }

    public function viewAction($id)
    {
        $existId = $this->verifyID($id);

        if ($existId == 1)
        {
            $registry = framework\Registry::getInstance();
            $conn = $registry->getParam('db');

            $sql = " SELECT * FROM libraryBook";

            $results = $conn->prepare($sql);
            $results->execute();

            $rows = $results->fetchAll();

            foreach ($rows as $row) {

                if ($row['ean'] === $id) {

                    echo ' 
                            
                            
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h2 class="page-header">' . $row['title'] . '</h2>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">Author: ' . $row['author'] . '</li>
                                        <li class="list-group-item">ISBN: ' . $row['ean'] . '</li>
                                        <li class="list-group-item">Updated on: ' . $row['updated_date'] . '</li>
                                    </ul>
                                </div>
                            </div>
                            ';
                    break;
                }
            }
        } else {
            echo 'Page does not exists.';
        }

    }

    public function editAction ($id)
    {
        $book = $this->getBookById($id);

        $this->createForm($book);

        $book = $this->gettingBook();

        $errorMessage = $this->validation($book);

        if (empty($errorMessage) === true) {

            $this->updateBook($id,$book);
            header('Location: index.php?controller=bookcontroller&action=listaction');
        }
    }

    private function createForm($book)
    {

        echo '

    <form method="post" action="#">
        <div class="form-group">
            <label>Book Title</label>
            <input name="title"type="text" class="form-control" placeholder="Enter the book title" value="' . $book["title"] . '">
        </div>
        <div class="form-group">
            <label>Book Author</label>
            <input name="author" type="text" class="form-control"  placeholder="Enter the book author" value="' . $book["author"] . '">
        </div>
        <div class="form-group">
            <label>Book EAN</label>
            <input name="ean" type="text" class="form-control"  placeholder="Enter the book EAN" value="' . $book["ean"] . '">
            <small class="form-text text-muted">You can find it over the barcode of the book</small>
        </div>
            <button name="submit" value="send" type="submit" class="btn btn-primary">Submit</button>
    </form>

';
    }

    private function gettingBook()
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

    private function validation($book)

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
            $errorMessage = '<b>Please enter the values and submit </b>';
        }

        echo $errorMessage;

        return $errorMessage;


    }

    private function insertBook ($book)
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

    private function verifyID ($id)
    {
        $registry = framework\Registry::getInstance();
        $conn = $registry->getParam('db');

        $sql = " SELECT ean, title, author, updated_date FROM libraryBook";
        $results = $conn->prepare($sql);
        $results->execute();

        $rows = $results->fetchAll();

        $existId = null;
        foreach ($rows as $row) {

                if ($row['ean'] === $id) {
                $existId = 1;
                break;
            }
        }
        return $existId;
    }

    private function getBookById ($id) {
        $registry = framework\Registry::getInstance();
        $conn = $registry->getParam('db');

        $sql = " SELECT ean, title, author, updated_date FROM libraryBook";
        $results = $conn->prepare($sql);
        $results-> execute();
        $rows = $results->fetchAll();

        foreach ($rows as $row)
        {
            $book = null;
            if ($row['ean']==$id)

            {
                $book = array(
                    'title' =>$row['title'],
                    'author'=>$row['author'],
                    'ean' =>$row['ean'],
                    'datetime' =>$row['updated_date']
                );

                break;
            }

        }
        return $book;
    }



    private function updateBook ($id, $book)
    {

        $registry = framework\Registry::getInstance();
        $conn = $registry->getParam('db');

        try {
            $sql = sprintf(
                "UPDATE libraryBook SET ean='%s', title='%s', author='%s', updated_date='%s' WHERE ean=%s",
                $book['ean'],
                $book['title'],
                $book['author'],
                $book['datetime'],
                $id
            );
            $conn->exec($sql);
        } catch (PDOException $e) {
            echo "Wait!!! :( Connection failed: " . $e->getMessage();
        }

    }

}

?>