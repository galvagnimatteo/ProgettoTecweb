<?php
	
	include "SingletonDB.php";
	
	if (!isset($_POST["numTicketIntero"], $_POST["numTicketRidotto"], $_POST["catPosti"], 
			   $_POST["modSelezPosti"], $_POST["idproiez"], $_POST["orario"], $_POST["seats"], $_POST["numSala"])) 
	{
		
		header("Location: 500.php");
		die();
	}
	
	$numTicketIntero = intval($_POST["numTicketIntero"]);
	$numTicketRidotto = intval($_POST["numTicketRidotto"]);
	$totBiglietti = $numTicketIntero + $numTicketRidotto;
	
	if ($_POST["modSelezPosti"]== "auto") {
		
		$postiVicini = isset($_POST["postiVicini"]);
		
		$catPosti = $_POST["catPosti"];
		
		//mappa posti
		$db = SingletonDB::getInstance();
		$db->connect();
		$preparedQuery = $db
			->getConnection()
			->prepare("SELECT Posto.Numero, Posto.Fila FROM Posto INNER JOIN Sala ON Sala.Numero = Posto.NumeroSala WHERE Sala.Numero = ? ORDER BY Posto.Fila, Posto.Numero");
		$preparedQuery->bind_param("i", $sala);
		$preparedQuery->execute();
		$result1 = $preparedQuery->get_result();
		
		$preparedQuery2 = $db
			->getConnection()
			->prepare("SELECT NumeroPosto as Numero, FilaPosto as Fila FROM Prenotazione INNER JOIN Partecipa ON Prenotazione.ID=Partecipa.IDPrenotazione WHERE Prenotazione.IDProiezione=? AND Prenotazione.OraProiezione=?");
		$preparedQuery2->bind_param("is", $_POST["idproiez"], $_POST["orario"]);
		$preparedQuery2->execute();
		$result2 = $preparedQuery2->get_result();
				
		$db->disconnect();
		
		$statoPosti = array();

		if (!empty($result1) && $result1->num_rows) {
			
			while($row = $result1->fetch_assoc()) {
				$statoPosti[$row["Fila"]][$row["Numero"]] = 'libero';  //inizializzo tutti liberi
			}
			
			if(!empty($result2) && $result2->num_rows > 0){ //se ci sono posti occupati
				while($row = $result2->fetch_assoc()) {
					$statoPosti[$row["Fila"]][$row["Numero"]] = 'occupato';  //posti occupati
				}
			}
			
			
			
			if ($catPosti == "davanti") {
				
			} 
			
			
			
			unset($statoPosti);
		} else {
			unset($statoPosti);
			header("Location: 500.php");
			die();
		}
		
	} else if ($_POST["modSelezPosti"] == "manual") {
		
		prenotaPosti($_POST["seats"], "username4", $totBiglietti, $_POST["idproiez"], $_POST["orario"], $_POST["numSala"]);
		
		echo "ok";
	}
	
	
	
	
	

?>