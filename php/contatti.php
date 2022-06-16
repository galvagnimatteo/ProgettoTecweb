<?php
session_start();
require_once "utils/generaPagina.php";
//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta
$home_content = file_get_contents("../html/contatti.html"); //load content

$title = "Contatti - PNG Cinema";
$keywords =
    "pngcinema, padova, contatti, dove, siamo, telefono, mail, email, telegram, twitter";
$description =
    "Pagina dei contatti di png cinema: è possibile reperire le informazioni su come contattarci nonché l'indirizzo e i social.";
$breadcrumbs = '<a href="home.php"><span lang="en">Home</span></a> / Contatti';
$jshead = '';
$jsbody = '';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "Contatti",
    $home_content,
    $breadcrumbs,
    $title,
    $description,
    $keywords,
    "",
    ""
);
?>
