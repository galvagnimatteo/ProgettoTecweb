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
if(!isset($_GET['IDFilm'])){
    echo '{"status":"manca identificativo film"}';
}
$db = SingletonDB::getInstance();
$reply=new \stdClass();
$reply->status="none";
if (isset($_POST['action'])&&$_POST['action']=='add') 
{

    if(isset($_POST['IDCast']))
        {
        $IDFilm = $_POST['IDFilm'];
        $IDCast = $_POST['IDCast'];        

        $query =
            'INSERT INTO Afferisce ( IDFilm,IDCast) VALUES (?,?)';
        $preparedQuery = $db->getConnection()->prepare($query);
        $preparedQuery->bind_param(
            'ss',
            $IDFilm,
            $IDCast
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
    if (isset($_POST['action'])&&$_POST['action']=='remove'){
        $idcast = $_POST['$IDCast'];
        $idfilm=$_POST['IDFilm'];
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
$cast;
$preparedQuery = $db
    ->getConnection()
    ->query('SELECT CastFilm.* ,Afferisce.IDFilm FROM CastFilm,Afferisce WHERE Afferisce.IDFilm=? AND Afferisce.IDCast=CastFilm.ID');    
    $preparedQuery->bind_param(
        's',
        $IDFilm        
    );
$cast_query=$preparedQuery->execute();        
    $preparedQuery->close();
$db->disconnect();
$i=0;
while ($row = $cast_query->fetch_assoc()) { 
    $castmember=new \stdClass();
    $castmember->Nome=$row['Nome'];
    $castmember->ID=$row['ID'];
    $castmember->Cognome=$row['Cognome'];
    $castmember->Lingua=$row['Lingua'];
    $castmember->Ruolo=$row['Ruolo'];
    $cast[$i]=$castmember;
    $i++;
}
$reply->cast=$cast;
echo json_encode($reply);

?>
