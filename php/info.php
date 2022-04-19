<?php
session_start();
include "utils/pageGenerator.php";
//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta
$home_content = file_get_contents("../html/info_content.html"); //load content

$title = "Info e Costi - PNG Cinema";
$keywords = "info, costi, costo biglietto, convenzioni, sconti";
$description =
    "Pagina informativa sui costi: è possibile consultare prezzi e convenzioni sui biglietti.";
$breadcrumbs = '<a href="home.php">Home</a> / Info e Costi';

//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "Info e Costi",
    $home_content,
    $breadcrumbs,
    $title,
    $description,
    $keywords,
    "",
    ""
);
?>
