<?php

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
    session_unset();
    session_destroy();
    session_start();
}

$_SESSION["discard_after"] = $now + 400;

$document = file_get_contents("../html/template.html"); //load template

$content = "";
if(isset($_SESSION["admin"])&&$_SESSION["admin"]){
    $content = file_get_contents("../html/admin.html"); //load content
}
else{
    $content = file_get_contents("../html/unautorized.html"); //messaggio di tentato acesso senza autorizzazioni e di effettuare il login
}
$document = str_replace(
    "<BREADCRUMB>",
    '<a href="./home.php" lang="en">Home</a>/amministrazione',
    $document
);

$document = str_replace('<PAGETITLE>', 'amministrazione - PNG Cinema', $document);
$document = str_replace('<KEYWORDS>', '', $document);


if (isset($_SESSION["a"])) {
    $document = str_replace("<LOGIN>", $_SESSION["a"], $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=getProfile",
        $document
    );

    $home_content = file_get_contents("../html/home_content.html");
} else {
    $document = str_replace("<LOGIN>", "Login", $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=login_page",
        $document
    );
}

$document = str_replace("/php/admin.php", "#", $document);
$document = str_replace("<CONTENT>", $content, $document);
echo $document;

?>
