<?php

//Get the value of the content_page (Current page)

$contentPage = 'default';
if(isset($_GET['action'])){
    $contentPage = $_GET['action'];
}

//Resets all the active values to null

$activeHomepage="";
$activeViewlist="";
$activeAddbook="";

//Switch to set the active page
switch ($contentPage) {
    case 'default':
        $activeHomepage="active";
        break;
    case 'listaction':
        $activeViewlist="active";
        break;
    case 'addaction':
        $activeAddbook="active";
        break;
}


//Changes of the CSS/Bootstrap properties depending on the active page
echo '
<div class="bs-example" data-example-id="simple-nav-stacked">
    <ul class="nav nav-tabs">
          <li role="presentation" class="' . $activeHomepage . '">
                <a href="?controller=default&action=default">Home</a>
          </li>
          <li role="presentation" class="' . $activeViewlist . '">
                <a href="?controller=bookcontroller&action=listaction">View book list</a>
          </li>
          <li role="presentation" class="' . $activeAddbook . '">
                <a href="?controller=bookcontroller&action=addaction">Add book list</a>
          </li>
    </ul>
</div>
          
          ';

?>