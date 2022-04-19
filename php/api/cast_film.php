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
if (isset($_POST['action'])&&$_POST['action']=='add') 
{

    if(isset($_POST['film']) &&
            isset($_POST['sala']) &&
            isset($_POST['Giorno']) 
            )
        {
            
        $query =
            'INSERT INTO Proiezione ( Data,IDFilm,NumeroSala) VALUES (?,?,?)';
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            'sss',
            $Data,
            $IDFilm,
            $NumeroSala
        );

        $IDFilm = $_POST['film'];
        $NumeroSala = $_POST['sala'];
        $Data = $_POST['Giorno'];

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
    if (isset($_POST['action'])&&$_POST['action']=='remove'){
        $idcast = $_POST['idcast'];
        $idfilm=$_POST['idfilm'];
        $query =
            'delete FROM Afferisce where IDCast=? AND IDFilm=?;';
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            's',
            $id
        );

        $res=$preparedQuery->execute();        
        $preparedQuery->close();
    }
    
}
$proiezioni;
$resultproiezioni = $db
    ->getConnection()
    ->query('SELECT  FROM CastFilm,Film,Afferisce WHERE Film.ID=Afferisce.IDFilm AND Afferisce.IDCast=CastFilm.ID');
$db->disconnect();
$i=0;
while ($row = $resultproiezioni->fetch_assoc()) { 
    $proiezione=new \stdClass();
    $proiezione->data=$row['Data'];
    $proiezione->id=$row['ID'];
    $proiezione->idfilm=$row['IDFilm'];
    $proiezione->titolofilm=$row['Titolo'];
    $proiezione->numeroSala=$row['NumeroSala'];
    $proiezioni[$i]=$proiezione;
    $i++;
}
$reply->proiezioni=$proiezioni;
echo json_encode($reply);

?>
