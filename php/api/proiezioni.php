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
//if(!isset($_SESSION['admin'])||!$_SESSION['admin']){
//    echo '{"status":"unauthorized"}';
//    exit();
//}
$status='"ok"';
$reply= '{';
if (isset($_Post['action'])&&$_Post['action']=='insert') 
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
        /*$db->disconnect();*/
        $preparedQuery->close();
        if($res){
            $status='"ok"';
        }
        else{
            $status='"database error"';
        }
    }
    else {
        $status='"parametri insufficenti"';
	}
}
$reply=$reply.'"status":'.$status.',';
$reply=$reply.'"Proiezione":[';
$db = SingletonDB::getInstance();
$resultproiezioni = $db
    ->getConnection()
    ->query('SELECT * FROM Proiezione');
$db->disconnect();
while ($row = $resultproiezioni->fetch_assoc()) { 
$reply=$reply.'{ "data":"'.$row['Data'].'",'.
                '"idfilm":"'.$row['IDFilm'].'",'.                
                '"numeroSala":"'. $row['NumeroSala'].'"},';
}
$reply=$reply.']}';
$reply=str_replace(',]',']',$reply);
echo $reply;

?>
