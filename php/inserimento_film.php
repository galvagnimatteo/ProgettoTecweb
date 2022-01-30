<?php
require_once "SingletonDB.php";

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
    session_unset();
    session_destroy();
    session_start();
}

$_SESSION["discard_after"] = $now + 30;


if($_SESSION["admin"]==false){
    header("Location: ./admin.php");
    exit();
}




$document = file_get_contents("../html/template.html"); //load template
$content = file_get_contents("../html/admin_inserimento_film_content.html"); //load content
$document = str_replace(
    "<BREADCRUMB>",
    '<a href="home.php">Home</a> / <a href="admin.php">amministrazione</a>/inserimento film',
    $document
);
$document = str_replace("<JAVASCRIPT-HEAD>", "", $document);
$document = str_replace("<JAVASCRIPT-BODY>", "", $document);


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
                $content=str_replace("<STATUS>", "<p class='sucess'>inserimento avvenuto correttamente</p>", $content)
            }
            else{
                $content=str_replace("<STATUS>", "<p class='faliure'>errore nell'inserimento prego riprovare</p>", $content)
            }
        }
    }
}
else{
    $content=str_replace("<STATUS>", "", $content)
}

$document = str_replace("<CONTENT>", $content, $document);

echo $document;

?>