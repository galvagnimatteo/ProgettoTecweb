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
    if(isset($_POST['Titolo']) &&
        isset($_POST['Genere']) &&
        isset($_POST['DataUscita']) &&
        isset($_POST['Descrizione']) &&
        isset($_POST['SrcImg']) &&
        isset($_POST['CarouselImg']) &&
        isset($_POST['Attori'])&&
        isset($_POST['Regista'])&&
        isset($_POST['Durata'])
        )
    {
        $Titolo         = return_cleaned($_POST['Titolo']);
        $Genere         = return_cleaned($_POST['Genere']);
        $DataUscita     = $_POST['DataUscita'];
        $Descrizione    = return_cleaned($_POST['Descrizione']);
        $SrcImg         = return_cleaned($_POST['SrcImg']);
        $Durata         = $_POST['Durata'];
        $CarouselImg    = return_cleaned($_POST['CarouselImg']);
        $Attori         = return_cleaned($_POST['Attori']);
        $Regista        = return_cleaned($_POST['Regista']);

        $check= CheckFilm(
            $Titolo,
            $Genere,
            $DataUscita,
            $Descrizione,
            $SrcImg,
            $Durata,
            $CarouselImg,
            $Attori,
            $Regista
        );
        if($check=="OK"){
            $query =
                'INSERT INTO Film ( Titolo,Genere,DataUscita, Descrizione,SrcImg,Durata,CarouselImg,Attori,Registi) VALUES (?,?,?,?,?,?,?,?,?)';
            $preparedQuery = $connection->prepare($query);
            $preparedQuery->bind_param(
                'sssssssss',
                $Titolo,
                $Genere,
                $DataUscita,
                $Descrizione,
                $SrcImg,
                $Durata,
                $CarouselImg,
                $Attori,
                $Regista
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
}
else{
    if (isset($_POST['action'])&&$_POST['action']=='delete')
    {
        $id = $_POST['idfilm'];
        $query='SELECT count(*) FROM Proiezione where IDFilm=?';
        $preparedQuery = $connection->prepare($query);
        $preparedQuery->bind_param(
            's',
            $id
        );
        $preparedQuery->execute();
        $res=$preparedQuery->get_result();
        if($res->fetch_array(MYSQLI_NUM)[0]==0){
            $query =
                'delete FROM Film where ID=?;';
            $preparedQuery = $connection->prepare($query);
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
                $reply->status="errore interno";
            }
        }
        else {
            $reply->status="impossibile eliminare un Film con proiezioni";
        }
    }
}
$films;
$resultFilms = $connection
    ->query('SELECT * FROM Film WHERE DATEDIFF(DataUscita, CURRENT_DATE())>= -35 ORDER BY DataUscita DESC');
    $connection->commit();//la transazione assicura che la lettura avvenga dopo gli inserimenti
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
