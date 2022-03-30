<?php
session_start();
include "utils/pageGenerator.php";

CheckSession(false,false); //verifica che la sessione sia un utente loggato ed un admin
$content=file_get_contents("../html/admin.html");
$jsbody='<script type="text/javascript" src="../js/admin.js"> </script>';
//   GeneratePage($page,  $content,$breadcrumbs,$title,                     $description,$keywords,$jshead,$jsbody);
echo GeneratePage("admin",$content,"admin"     ,"amministazione-PNG Cinema","",          "",       "",     $jsbody);

?>
