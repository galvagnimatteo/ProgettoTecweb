<?php
session_start();
require_once "utils/generaPagina.php";
$document = file_get_contents("../html/template.html"); //load template
$error_content = file_get_contents("../html/404.html"); //load content
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
$breadcrumbs = '<a href="home.php">Home</a> / Errore 404';

echo GeneratePage(
    "404",
    $error_content,
    $breadcrumbs,
    "404 - PNG Cinema",
    "Errore: pagina non trovata. Forse hai digitato il link sbagliato.",
    "",
    "",
    ''
);
$document = str_replace("<PAGETITLE/>", "404 - PNG Cinema", $document);
?>
