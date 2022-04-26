<?php
session_start();
require_once '../utils/SingletonDB.php';
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
$db = SingletonDB::getInstance();
$reply=new \stdClass();
$reply->status="none";
$connection=$db->getConnection();
$connection->begin_transaction();
if (isset($_POST['action'])&&$_POST['action']=='insert') 
{

    if(isset($_POST['Nome']) &&
            isset($_POST['Cognome']) &&
            isset($_POST['Ruolo']) &&
            isset($_POST['Lingua']) 
            )
        {
        $Nome=$_POST['Nome'];        
        $Cognome=$_POST['Cognome'];
        $Lingua=$_POST['Lingua'];
        $Ruolo=$_POST['Ruolo'];        
            
        $query =
            'INSERT INTO CastFilm ( Nome,Cognome,Lingua,Ruolo) VALUES (?,?,?,?)';
        $preparedQuery = $connection->prepare($query);
        $preparedQuery->bind_param(
            'ssss',
            $Nome,
            $Cognome,
            $Lingua,
            $Ruolo
        );       

        $res=$preparedQuery->execute();        
        $preparedQuery->close();
        if($res){
            $reply->status="ok";
        }
        else{
            $reply->status="errore interno";
        }
    }
    else {
        $reply->status="parametri insufficenti";
	}
}else{
    if (isset($_POST['action'])&&$_POST['action']=='delete'){
        $idcast = $_POST['IDCast'];        
        $query =
            'delete FROM CastFilm where ID=?;';
        $preparedQuery = $connection->prepare($query);
        $preparedQuery->bind_param(
            's',
            $idcast
        );
        $res=$preparedQuery->execute();
        if($res){
            $reply->status="ok";
        }
        else 
        {
            $reply->status="errore interno";
        }

        $preparedQuery->close();
    }
}
$people;
$resultcast = $connection
    ->query('SELECT * FROM CastFilm');
$connection->commit();//la transazione assicura che la lettura avvenga dopo gli inserimenti
$db->disconnect();
$i=0;
while ($row = $resultcast->fetch_assoc()) { 
    $person=new \stdClass();
    $person->Nome=$row['Nome'];
    $person->ID=$row['ID'];
    $person->Cognome=$row['Cognome'];
    $person->Lingua=$row['Lingua'];
    $person->Ruolo=$row['Ruolo'];
    $people[$i]=$person;
    $i++;
}
$reply->cast_people=$people;
echo json_encode($reply);

?>