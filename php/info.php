<?php
session_start();
$document = file_get_contents("../html/template.html"); //load template
$home_content = file_get_contents("../html/info_content.html"); //load content

$document = str_replace("<PAGETITLE>", "Info e Costi - PNG Cinema", $document);
$document = str_replace(
    "<KEYWORDS>",
    "info, costi, costo biglietto, convenzioni, sconti",
    $document
);
$document = str_replace(
    "<DESCRIPTION>",
    "Pagina informativa sui costi: è possibile consultare prezzi e convenzioni sui biglietti.",
    $document
);
$document = str_replace(
    "<BREADCRUMB>",
    '<a href="home.php">Home</a> / Info e Costi',
    $document
);

$document = str_replace("<JAVASCRIPT-HEAD>", "", $document);
$document = str_replace("<JAVASCRIPT-BODY>", "", $document);

if (isset($_SESSION["a"])) {
    $document = str_replace("<LOGIN>", $_SESSION["a"], $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=getProfile",
        $document
    );
} else {
    $document = str_replace("<LOGIN>", "Login", $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=login_page",
        $document
    );
}
if($_SESSION["admin"]){
    $document = str_replace("<ADMIN>","<li><a href='admin.php'>Amministrazione</a></li>",$document);
}
else{
    $document = str_replace("<ADMIN>","",$document);
}

$document = str_replace("/php/info.php", "#", $document);
$document = str_replace("<CONTENT>", $home_content, $document);

echo $document;

?>
