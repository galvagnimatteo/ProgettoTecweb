<?php
session_start();
include "utils/generaPagina.php";

CheckSession(true, true); //verifica che la sessione sia un utente loggato ed un admin
$content = file_get_contents("../html/amministrazione.html");
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
$breadcrumbs = '<a href="home.php">Home</a> /<a href="area_utenti.php">Login</a>/ Amministazione';
echo GeneratePage(
    "admin",
    $content,
    $breadcrumbs,
    "Amministazione - PNG Cinema",
    "",
    "",
    "",
    '<script type="text/javascript" src="../js/admin.js"> </script>'
);

?>
