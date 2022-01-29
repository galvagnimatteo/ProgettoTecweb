<?php
	session_start();
    $document = file_get_contents('../html/template.html'); //load template
    $error_content = file_get_contents('../html/500.html'); //load content

    $document = str_replace('<PAGETITLE>', '500 - PNG Cinema', $document);
    $document = str_replace('<KEYWORDS>', 'errore, errore server, errore risposta', $document);
    $document = str_replace('<DESCRIPTION>', 'Errore: il server ha risposto con un errore.', $document);
    $document = str_replace('<BREADCRUMB>', '<a href="500.php">Errore 500</a>', $document);

    $document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
    $document = str_replace('<JAVASCRIPT-BODY>', '', $document);

    $document = str_replace('<CONTENT>', $error_content, $document); //fills template with content
	//SESSION
	if (isset($_SESSION["a"])) {
    $document = str_replace("<LOGIN>", $_SESSION["a"], $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=getProfile",
        $document
    );
	} else {
    $document = str_replace("<LOGIN>", "Login", $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=login_page",
        $document
    );
	}
    echo($document);

?>