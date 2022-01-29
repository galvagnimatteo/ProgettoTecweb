<?php

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
    session_unset();
    session_destroy();
    session_start();
}

$_SESSION["discard_after"] = $now + 30;

$document = file_get_contents("../html/template.html"); //load template

$content = "";
if($_SESSION["admin"]=true){
    $content = file_get_contents("../html/admin.html"); //load content
}
else{
    $content = file_get_contents("../html/unautorized.html"); //messaggio di tentato acesso senza autorizzazioni e di effettuare il login
}
$document = str_replace(
    "<BREADCRUMB>",
    'amministrazione',
    $document
);
$document = str_replace("<JAVASCRIPT-HEAD>", "", $document);
$document = str_replace("<JAVASCRIPT-BODY>", "", $document);



$document = str_replace("<CONTENT>", $content, $document);
echo $document;

?>
