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
    exit();
}
$db = SingletonDB::getInstance();
$reply=new \stdClass();
$reply->status="none";
if (isset($_POST['action'])&&$_POST['action']=='add') 
{

    if(isset($_POST['IDCast'])&isset($_POST['IDFilm']))
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
            'ss',
            $idcast,
            $idfilm
        );

        $res=$preparedQuery->execute();        
        $preparedQuery->close();
    }
    
}
$cast;
$preparedQuery = $db
    ->getConnection()
    ->prepare('SELECT CastFilm.* FROM CastFilm,Afferisce WHERE Afferisce.IDFilm = ? AND Afferisce.IDCast=CastFilm.ID;');    
    $preparedQuery->bind_param(
        's',
        $_GET['IDFilm']
    );
$preparedQuery->execute();
$cast_query=$preparedQuery->get_result();
    $preparedQuery->close();
$db->disconnect();
$i=0;
if($cast_query->num_rows > 0){    
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
}
else {
	$reply->cast=null;
}
echo json_encode($reply);

?>
