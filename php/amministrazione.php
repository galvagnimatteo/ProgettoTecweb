<?php
session_start();
require_once "utils/generaPagina.php";

CheckSession(true, true); //verifica che la sessione sia un utente loggato ed un admin
$content = file_get_contents("../html/admin.html");
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "admin",
    $content,
    "Amministrazione",
    "Amministrazione - PNG Cinema",
    "",
    "",
    "",
    ""
);

?>
