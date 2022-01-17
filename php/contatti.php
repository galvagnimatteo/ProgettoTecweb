<?php

    $document = file_get_contents('../html/template.html'); //load template
    $home_content = file_get_contents('../html/contatti_content.html'); //load content
    $document = str_replace('<CONTENT>', $home_content, $document); //fills template with content
    $document = str_replace('<BREADCRUMB>', '<a href="home.php">Home</a> / <a href="contatti.php">Contatti</a>', $document);
    $document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
    $document = str_replace('<JAVASCRIPT-BODY>', '', $document);

    echo($document);

?>