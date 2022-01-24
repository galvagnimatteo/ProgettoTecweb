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


if($_SESSION["admin"]=false){
    header("Location: ./admin.php");
    exit();
}




$document = file_get_contents("../html/template.html"); //load template
$content = file_get_contents("../html/area_utenti_register_content.html"); //load content
$document = str_replace(
    "<BREADCRUMB>",
    '<a href="home.php">Home</a> / <a href="area_utenti.php">Area Utenti</a>',
    $document
);
$document = str_replace("<JAVASCRIPT-HEAD>", "", $document);
$document = str_replace("<JAVASCRIPT-BODY>", "", $document);


if (isset($_GET["action"])) 
{
    $action = $_GET["action"];
    if($action=="insert")
    {
        if(isset($_POST["name_register"]) &&
            isset($_POST["username_register"]) &&
            isset($_POST["password_register"]) &&
            isset($_POST["email_register"]) &&
            isset($_POST["surname_register"])
            )
        {
            $db = SingletonDB::getInstance();

            $query =
                "INSERT INTO utente ( username,email,nome, cognome,password) VALUES (?,?,?,?,?)";
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param(
                "sssss",
                $username,
                $email,
                $name,
                $surname,
                $password
            );

            $username = $_POST["username_register"];
            $password = $_POST["password_register"];
            $name = $_POST["name_register"];
            $surname = $_POST["surname_register"];
            $email = $_POST["email_register"];


            $preparedQuery->execute();
            $db->disconnect();
            $preparedQuery->close();

        }
    }
}

$document = str_replace("<CONTENT>", $content, $document);
echo $document;

?>
