<?php

	include "SingletonDB.php";
	
	if(!isset($_POST["sceltafilm"],$_POST["sceltadata"], $_POST["sceltaora"] )) {
		header("Location: 404.php");
        die();
	}
	
	$db = SingletonDB::getInstance();
	
	$preparedQuery = $db
        ->getConnection()
        ->prepare("SELECT Proiezione.ID FROM Film INNER JOIN Proiezione ON Film.ID=Proiezione.IDFilm WHERE Film.ID=? AND Proiezione.Data=? "); 
	$preparedQuery->bind_param("is", $_POST["sceltafilm"], $_POST["sceltadata"] );
    $preparedQuery->execute();
    $result1 = $preparedQuery->get_result();
	$db->disconnect();
	
	
	
	
	
	if(!empty($result1) && $result1->num_rows > 0) 
	{
		$dati = $result1->fetch_assoc();
		
		header("Location:prenotazione.php?idproiez=" . $dati["ID"] . "&orario=" . $_POST["sceltaora"]);
		
		die();
	} 
	else 
	{
		header("Location: 404.php");
        die();
	}

?>