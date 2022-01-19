<?php

    $document = file_get_contents('../html/template.html'); //load template
    $error_content = file_get_contents('../html/404.html'); //load content

    $document = str_replace('<PAGETITLE>', '404 - PNG Cinema', $document);
    $document = str_replace('<KEYWORDS>', 'errore, non trovato, pagina non trovata, 404', $document);
    $document = str_replace('<DESCRIPTION>', 'Errore: pagina non trovata. Digitato il link sbagliato?', $document);
    $document = str_replace('<BREADCRUMB>', '<a href="404.php">Errore 404</a>', $document);

    $document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
    $document = str_replace('<JAVASCRIPT-BODY>', '', $document);

    $document = str_replace('<CONTENT>', $error_content, $document); //fills template with content

    echo($document);

?>