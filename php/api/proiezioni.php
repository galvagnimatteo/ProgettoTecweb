<?php
session_start();
require_once '../utils/controlli.php';
require_once '../utils/SingletonDB.php';
$now = time();
    if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['discard_after'] = $now+200;
if(!isset($_SESSION['admin'])||!$_SESSION['admin']){
    echo '{"status":"sessione non autenticata come amministratore"}';
    exit();
}
$db = SingletonDB::getInstance();
$reply=new \stdClass();
$reply->status="none";
$connection=$db->getConnection();
$connection->begin_transaction();
if (isset($_POST['action'])&&$_POST['action']=='insert')
{

    if(isset($_POST['film']) &&
            isset($_POST['sala']) &&
            isset($_POST['Orario']) &&
            isset($_POST['Giorno'])
            )
    {
            $IDFilm      = $_POST['film'];
            $NumeroSala  = $_POST['sala'];
            $Data        = $_POST['Giorno'];
            $orario      = $_POST['Orario'];



        $check=CheckProiezione(
            $connection,
            $Data,
            $IDFilm,
            $NumeroSala,
            $orario
        );
        if($check=="OK"){
            $query =
                'INSERT INTO Proiezione ( Data,IDFilm,NumeroSala,Orario) VALUES (?,?,?,?)';
            $preparedQuery = $connection->prepare($query);
            $preparedQuery->bind_param(
                'ssss',
                $Data,
                $IDFilm,
                $NumeroSala,
                $orario
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
        else{
            $reply->status=$check;
        }
    }
    else {
        $reply->status="parametri insufficenti";
	}
}else{
    if (isset($_POST['action'])&&$_POST['action']=='delete'){
        $id = $_POST['idproiezione'];
        $query='SELECT count(*) FROM Prenotazione where IDProiezione=?';
        $preparedQuery = $connection->prepare($query);
        $preparedQuery->bind_param(
            's',
            $id
        );
        $preparedQuery->execute();
        $res=$preparedQuery->get_result();
        if($res->fetch_array(MYSQLI_NUM)[0]==0){
            $query =
                'delete FROM Proiezione where ID=?;';
            $preparedQuery = $connection->prepare($query);
            $preparedQuery->bind_param(
                's',
                $id
            );

            $res=$preparedQuery->execute();
            if($res){
                $reply->status="ok";
            }
            else{
                $reply->status="errore interno";
            }
            $preparedQuery->close();
        }
        else {
            $reply->status="impossibile eliminare una proiezione con prenotazioni";
        }
    }
}
$proiezioni;
$resultproiezioni = $connection
    ->query("SELECT Data, Proiezione.ID as ID, IDFilm, Titolo, NumeroSala, Orario, Durata FROM Proiezione INNER JOIN Film ON Film.ID=Proiezione.IDFilm WHERE Data > current_date");
    $connection->commit();//la transazione assicura che la lettura avvenga dopo le modifiche

$db->disconnect();
$i=0;
while ($row = $resultproiezioni->fetch_assoc()) {
    $proiezione=new \stdClass();
    $proiezione->data=$row['Data'];
    $proiezione->id=$row['ID'];
    $proiezione->idfilm=$row['IDFilm'];
    $proiezione->titolofilm=$row['Titolo'];
    $proiezione->numeroSala=$row['NumeroSala'];
    $proiezione->orario=$row['Orario'];
    $proiezione->durata=$row['Durata'];
    $proiezioni[$i]=$proiezione;
    $i++;
}
$reply->proiezioni=$proiezioni;
echo json_encode($reply);

?>
