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
$db = SingletonDB::getInstance();
$reply=new \stdClass();
$reply->status="none";
if (isset($_POST['action'])&&$_POST['action']=='insert') 
{

    if(isset($_POST['Nome']) &&
            isset($_POST['Cognome']) &&
            isset($_POST['Ruolo']) &&
            isset($_POST['Lingua']) 
            )
        {
        $Nome=$row['Nome'];        
        $Cognome=$row['Cognome'];
        $Lingua=$row['Lingua'];
        $Ruolo=$row['Ruolo'];        
            
        $query =
            'INSERT INTO CastFilm ( Nome,Cognome,Lingua,Ruolo) VALUES (?,?,?,?)';
        $preparedQuery = $db->getConnection()->prepare($query);
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
            $reply->status=$res;
        }
        else{
            $reply->status="database error";
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
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            's',
            $idcast
        );

        $res=$preparedQuery->execute();        
        $preparedQuery->close();
    }
    
}
$people;
$resultcast = $db
    ->getConnection()
    ->query('SELECT * FROM CastFilm');
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