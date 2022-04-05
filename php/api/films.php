<?php
session_start();
require_once '../SingletonDB.php';
 $now = time();
    if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['discard_after'] = $now+200;
if(!isset($_SESSION['admin'])||!$_SESSION['admin']){
    echo '{"status":"unauthorized"}';
    exit();
}
$reply=new \stdClass();
$reply->status="ok";
if (isset($_Post['action'])&&$_Post['action']=='insert') 
{
    if(isset($_POST['Titolo']) &&
        isset($_POST['Genere']) &&
        isset($_POST['DataUscita']) &&
        isset($_POST['Descrizione']) &&
        isset($_POST['SrcImg']) &&
        isset($_POST['AltImg']) &&
        isset($_POST['Durata'])
        )
    {
        $db = SingletonDB::getInstance();

        $query =
            'INSERT INTO Film ( Titolo,Genere,DataUscita, Descrizione,SrcImg,AltImg,Durata) VALUES (?,?,?,?,?,?,?)';
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            'sssssss',
            $Titolo,
            $Genere,
            $DataUscita,
            $Descrizione,
            $SrcImg,
            $AltImg,
            $Durata
        );

        $Titolo = $_POST['Titolo'];
        $Genere = $_POST['Genere'];
        $DataUscita = $_POST['DataUscita'];
        $Descrizione = $_POST['Descrizione'];
        $SrcImg = $_POST['SrcImg'];
        $AltImg = $_POST['AltImg'];
        $Durata = $_POST['Durata'];


        $res=$preparedQuery->execute();
        $db->disconnect();
        $preparedQuery->close();
        if($res){
            $reply->status="ok";
        }
        else{
            $reply->status="database error";
        }
    }
    else {
        $reply->status="parametri insufficenti";
	}
}
$films;
$db = SingletonDB::getInstance();
$resultFilms = $db
    ->getConnection()
    ->query('SELECT * FROM Film ORDER BY DataUscita DESC');
$db->disconnect();
$i=0;
while ($row = $resultFilms->fetch_assoc()) {
    $film=new \stdClass();
    $film->id=$row['ID'];
    $film->titolo=$row['Titolo'];
    $film->genere=$row['Genere'];
    $film->datauscita=$row['DataUscita'];
    $film->descrizione=$row['Descrizione'];
    $film->srcimg=$row['SrcImg'];
    $film->altimg=$row['AltImg'];
    $film->durata=$row['Durata'];                
    $films[$i]=$film;
    $i++;
}
$reply->films=$films;
echo json_encode($reply);

?>
