<?php

    include 'SingletonDB.php';

    $document = file_get_contents('../html/template.html'); //load template
    $home_content = file_get_contents('../html/home_content.html'); //load content

    $db = SingletonDB::getInstance();

    $document = str_replace('<CONTENT>', $home_content, $document); //fills template with content

    echo($document);

?>