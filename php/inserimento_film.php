<?php
session_start();
require_once "utils/SingletonDB.php";
require_once "utils/generaPagina.php";
//CheckSession($login_required, $admin_required);
CheckSession(true, true); //verifica che la sessione sia un utente loggato ed un admin

$content = file_get_contents("../html/inserimento_film.html"); //load content

if (isset($_GET["action"])) {
    $action = $_GET["action"];
    if ($action == "insert") {
        if (
            isset($_POST["Titolo"]) &&
            isset($_POST["Genere"]) &&
            isset($_POST["DataUscita"]) &&
            isset($_POST["Descrizione"]) &&
            isset($_POST["SrcImg"]) &&
            isset($_POST["AltImg"]) &&
            isset($_POST["Durata"])
        ) {
            $db = SingletonDB::getInstance();

            $query =
                "INSERT INTO Utente ( Titolo,Genere,DataUscita, Descrizione,SrcImg,AltImg,Durata) VALUES (?,?,?,?,?,?,?)";
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

            $res = $preparedQuery->execute();
            $db->disconnect();
            $preparedQuery->close();
            if ($res) {
                $content = str_replace(
                    "<STATUS>",
                    "<p class='success'>inserimento avvenuto correttamente</p>",
                    $content
                );
            } else {
                $content = str_replace(
                    "<STATUS>",
                    "<p class='faliure'>errore nell'inserimento prego riprovare</p>",
                    $content
                );
            }
        }
    }
} else {
    $content = str_replace("<STATUS>", "", $content);
}
$title = "Inserimento film - PNG Cinema";
$keywords = "";
$breadcrumb =
    '<a href="home.php">Home</a> / <a href="admin.php"> Amministrazione </a> / Inserimento film';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "inserimento film",
    $content,
    $breadcrumb,
    $title,
    "pagina di inserimento film",
    $keywords,
    "",
    ""
);

?>
