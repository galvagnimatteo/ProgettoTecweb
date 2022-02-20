<?php
session_start();
include "pageGenerator.php";

CheckSession(true,true); //verifica che la sessione sia un utente loggato ed un admin
$content=file_get_contents("../html/admin.html");
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage("admin",$content,"<p>admin</p>","amministazione-PNG Cinema","","","","");

?>
