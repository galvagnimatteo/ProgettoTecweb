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
$reply->status="none";
if (isset($_POST['action'])&&$_POST['action']=='insert') 
{
    if(isset($_POST['Titolo']) &&
        isset($_POST['Genere']) &&
        isset($_POST['DataUscita']) &&
        isset($_POST['Descrizione']) &&
        isset($_POST['SrcImg']) &&  
        isset($_POST['CarouselImg']) &&
        isset($_POST['Durata'])
        )
    {
        $db = SingletonDB::getInstance();        

        $Titolo = $_POST['Titolo'];
        $Genere = $_POST['Genere'];
        $DataUscita = $_POST['DataUscita'];
        $Descrizione = $_POST['Descrizione'];
        $SrcImg = $_POST['SrcImg'];
        //$AltImg = $_POST['AltImg'];
        $Durata = $_POST['Durata'];       
        $CarouselImg=$_POST['CarouselImg'];
        
        
        $query =
            'INSERT INTO Film ( Titolo,Genere,DataUscita, Descrizione,SrcImg,Durata,CarouselImg) VALUES (?,?,?,?,?,?,?)';
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            'sssssss',
            $Titolo,
            $Genere,
            $DataUscita,
            $Descrizione,
            $SrcImg,            
            $Durata,
            $CarouselImg
        );

        $res=$preparedQuery->execute();        
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
else{
    if (isset($_POST['action'])&&$_POST['action']=='delete')
    {
    $db = SingletonDB::getInstance();        

        $id = $_POST['idfilm'];
        $query =
            'delete FROM Film where ID=?;';
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            's',
            $id
        );

        $res=$preparedQuery->execute();        
        $preparedQuery->close();
        if($res){
            $reply->status="ok";
        }
        else{
            $reply->status="database error";
        }
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
    //$film->altimg=$row['AltImg'];
    $film->durata=$row['Durata'];                
    $films[$i]=$film;
    $i++;
}
$reply->films=$films;
echo json_encode($reply);

?>
