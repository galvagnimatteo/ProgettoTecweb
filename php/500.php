<?php
session_start();
require_once "utils/generaPagina.php";
$document = file_get_contents("../html/template.html"); //load template
$error_content = file_get_contents("../html/500.html"); //load content
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "error500",
    $error_content,
    "",
    "Error 500",
    "Errore: il server ha risposto con un errore.",
    "",
    "",
    ''
);
?>
