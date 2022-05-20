<?php
session_start();
require_once "utils/generaPagina.php";
$document = file_get_contents("../html/template.html"); //load template
$error_content = file_get_contents("../html/500.html"); //load content
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
$breadcrumbs = '<a href="home.php"><span lang="en">Home</span></a> / Errore 500';

echo GeneratePage(
    "error500",
    $error_content,
    $breadcrumbs,
    "500 - PNG Cinema",
    "Errore: il server ha risposto con un errore. Ci scusiamo del disagio, risolveremo al più presto.",
    "errore, 500",
    "",
    ''
);
?>
