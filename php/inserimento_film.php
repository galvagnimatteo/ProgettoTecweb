<?php
require_once "SingletonDB.php";

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
    session_unset();
    session_destroy();
    session_start();
}

$_SESSION["discard_after"] = $now + 400;


if(!isset($_SESSION["admin"])||!$_SESSION["admin"]){
    header("Location: ./admin.php");
    exit();
}




$document = file_get_contents("../html/template.html"); //load template
$content = file_get_contents("../html/admin_inserimento_film_content.html"); //load content
$document = str_replace('<PAGETITLE>', 'inserimento film - PNG Cinema', $document);
$document = str_replace('<KEYWORDS>', '', $document);
$document = str_replace(
    "<BREADCRUMB>",
    '<a href="home.php">Home</a> / <a href="admin.php">amministrazione</a>/inserimento film',
    $document
);



if (isset($_GET["action"])) 
{
    $action = $_GET["action"];
    if($action=="insert")
    {
        if(isset($_POST["Titolo"]) &&
            isset($_POST["Genere"]) &&
            isset($_POST["DataUscita"]) &&
            isset($_POST["Descrizione"]) &&
            isset($_POST["SrcImg"]) &&
            isset($_POST["AltImg"]) &&
            isset($_POST["Durata"]) 
            )
        {
            $db = SingletonDB::getInstance();

            $query =
                "INSERT INTO utente ( Titolo,Genere,DataUscita, Descrizione,SrcImg,AltImg,Durata) VALUES (?,?,?,?,?,?,?)";
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param(
                "sssssss",
                $Titolo,
                $Genere,
                $DataUscita,
                $Descrizione,
                $SrcImg,
                $AltImg,
                $Durata
            );

            $Titolo = $_POST["Titolo"];
            $Genere = $_POST["Genere"];
            $DataUscita = $_POST["DataUscita"];
            $Descrizione = $_POST["Descrizione"];
            $SrcImg = $_POST["SrcImg"];
            $AltImg = $_POST["AltImg"];
            $Durata = $_POST["Durata"];


            $res=$preparedQuery->execute();
            $db->disconnect();
            $preparedQuery->close();
            if($res){
                $content=str_replace("<STATUS>", "<p class='success'>inserimento avvenuto correttamente</p>", $content);
            }
            else{
                $content=str_replace("<STATUS>", "<p class='faliure'>errore nell'inserimento prego riprovare</p>", $content);
            }
        }
    }
}
else{
    $content=str_replace("<STATUS>", "", $content);
}
$document = str_replace("/php/inserimento_film.php", "#", $document);
$document = str_replace("<CONTENT>", $content, $document);

echo $document;

?>
