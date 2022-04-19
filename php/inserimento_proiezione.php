<?php
session_start();
require_once "utils/SingletonDB.php";
require_once "utils/generaPagina.php";
//CheckSession($login_required, $admin_required);
CheckSession(true,true); //verifica che la sessione sia un utente loggato ed un admin

$content = file_get_contents("../html/admin_inserimento_proiezione_content.html"); //load content


$db = SingletonDB::getInstance();

if (isset($_GET["action"]))
{
    $action = $_GET["action"];
    if($action=="insert")
    {
        if(isset($_POST["film"]) &&
            isset($_POST["sala"]) &&
            isset($_POST["Giorno"])
            )
        {

            $query =
                "INSERT INTO Proiezione ( Data,IDFilm,NumeroSala) VALUES (?,?,?)";
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param(
                "sss",
                $Data,
                $IDFilm,
                $NumeroSala
            );

            $IDFilm = $_POST["film"];
            $NumeroSala = $_POST["sala"];
            $Data = $_POST["Giorno"];

            $res=$preparedQuery->execute();
            /*$db->disconnect();*/
            $preparedQuery->close();
            if($res){
                $content=str_replace("<STATUS>", "<p class='success'>inserimento avvenuto correttamente</p>", $content);
            }
            else{
                $content=str_replace("<STATUS>", "<p class='failure'>errore nell'inserimento prego riprovare</p>", $content);
            }
        }
    }
}
else{
    $content=str_replace("<STATUS>", "", $content);
}
$query ="select ID,Titolo from Film ";
$preparedQuery = $db->getConnection()->prepare($query);
$preparedQuery->execute();
$films=$preparedQuery->get_result();

$stringfilms="";
while($row = $films->fetch_assoc()){
    $stringfilms=$stringfilms."<option value=".$row["ID"].">".$row["Titolo"]."</option>";
}
$content = str_replace("<FILM>", $stringfilms, $content);

$title = 'inserimento Proiezione - PNG Cinema';
$keywords = '';
$breadcrumb ='<a href="home.php">Home</a> / <a href="amministrazione.php">amministrazione</a>/inserimento Proiezione';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage("inserimento Proiezione",$content,$breadcrumb,$title,"pagina di inserimento proiezioni",$keywords,"","");
?>
