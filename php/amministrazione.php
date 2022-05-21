<?php
session_start();
include "utils/generaPagina.php";

CheckSession(true, true); //verifica che la sessione sia un utente loggato ed un admin
$content = file_get_contents("../html/amministrazione.html");
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
$breadcrumbs = '<a href="home.php"><span lang="en">Home</span></a> / Amministazione';
echo GeneratePage(
    "admin",
    $content,
    $breadcrumbs,
    "Amministazione - PNG Cinema",
    "Pagina di amministrazione di png cinema: è possibile visualizzare tutti i film e le proiezioni nonchè aggiungerne o eliminarne.",
    "admin, amministrazione, film, proiezioni, aggiungi, elimina",
    "",
    '<script type="text/javascript" src="../js/admin.js"> </script>'
);

?>
