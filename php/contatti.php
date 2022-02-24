<?php
session_start();
include "utils/pageGenerator.php";
//CheckSession($login_required, $admin_required);
CheckSession(false,false); //refresh della sessione se scaduta
$home_content = file_get_contents("../html/contatti_content.html"); //load content

$title ="Contatti - PNG Cinema";
$keywords ="contatti, indirizzo, numero, telefono, email, mail, telegram, mappa, indicazioni";
$description = "Pagina dei contatti: è possibile reperire le informazioni su come contattarci nonchè quelle su come arrivare al cinema.";
$breadcrumbs ='<a href="home.php">Home</a> / Contatti';
$jshead='<script type="text/javascript" src="../js/carousel.js"> </script>';
$jsbody='<script type="text/javascript" src="../js/jquery-3.6.0.min.js"> </script>
                            <script type="text/javascript" src="../js/quickpurchase.js"> </script>';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage("Contatti",$home_content,$breadcrumbs,$title,$description,$keywords,"","");
?>
