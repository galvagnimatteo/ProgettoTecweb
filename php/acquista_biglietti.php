<?php
	
	include "SingletonDB.php";
	
	/*$db = SingletonDB::getInstance();
	$db->connect();
	$preparedQuery = $db
		->getConnection()
		->prepare("SELECT Posto.Numero, Posto.Fila FROM Posto INNER JOIN Sala ON Sala.Numero = Posto.NumeroSala WHERE Sala.Numero = ? ORDER BY Posto.Fila, Posto.Numero");
	$preparedQuery->bind_param("i", $sala);
	$preparedQuery->execute();
	$result1 = $preparedQuery->get_result();
	
	$db->disconnect();*/
	
	if (isset($_POST["modSelezPosti"] && $_POST["modSelezPosti"]=="auto")) {
		
		
		
		
		
	}
	
	
	
	
	

?>