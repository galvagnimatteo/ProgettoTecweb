<?php
session_start();
include "utils/generaPagina.php";

CheckSession(true, true); //verifica che la sessione sia un utente loggato ed un admin
$content = file_get_contents("../html/amministrazione.html");
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "admin",
    $content,
    "<p>admin</p>",
    "amministazione-PNG Cinema",
    "",
    "",
    "",
    '<script type="text/javascript" src="../js/admin.js"> </script>'
);

?>
