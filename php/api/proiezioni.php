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
$reply=new \stdClass();
$reply->status="ok";
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
$proiezioni;
$db = SingletonDB::getInstance();
$resultproiezioni = $db
    ->getConnection()
    ->query('SELECT * FROM Proiezione');
$db->disconnect();
$i=0;
while ($row = $resultproiezioni->fetch_assoc()) { 
    $proiezione=new \stdClass();
    $proiezione->data=$row['Data'];
    $proiezione->idfilm=$row['IDFilm'];
    $proiezione->numeroSala=$row['NumeroSala'];
    $proiezioni[i]=$Proiezione;
    $i++;
}
$reply->proiezioni=$proiezioni;
echo json_encode($reply);

?>
