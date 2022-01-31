<?php
	session_start();
	include "SingletonDB.php";
	include "utils/prenotaPosti.php";
	
	if (!isset($_POST["numTicketIntero"], $_POST["numTicketRidotto"], $_POST["catPosti"], 
			   $_POST["modSelezPosti"], $_POST["idproiez"], $_POST["orario"], $_POST["seats"], 
			   $_POST["numSala"], $_POST["titoloFilm"], $_POST["dataOraIta"])) 
	{
		
		header("Location: 500.php");
		die();
	}
	
	$username = ''; //se resta '' inserisce null e l'utente era un ospite
	if (isset($_SESSION["a"])) 
		$username = $_SESSION["a"];
	
	$totNumBiglietti = intval($_POST["numTicketIntero"]) + intval($_POST["numTicketRidotto"]);
	
	if ($_POST["modSelezPosti"]== "auto") {
		
		$postiVicini = isset($_POST["postiVicini"]);
		
		$catPosti = $_POST["catPosti"];
		
		//mappa posti
		$db = SingletonDB::getInstance();
		$db->connect();
		$preparedQuery = $db  //post totali
			->getConnection()
			->prepare("SELECT Posto.Fila, Posto.Numero FROM Posto INNER JOIN Sala ON Sala.Numero = Posto.NumeroSala WHERE Sala.Numero = ? ORDER BY Posto.Fila, Posto.Numero");
		$preparedQuery->bind_param("i", $_POST["numSala"]);
		$preparedQuery->execute();
		$result1 = $preparedQuery->get_result();
		
		$preparedQuery2 = $db //posti occupati
			->getConnection()
			->prepare("SELECT NumeroPosto as Numero, FilaPosto as Fila FROM Prenotazione INNER JOIN Partecipa ON Prenotazione.ID=Partecipa.IDPrenotazione WHERE Prenotazione.IDProiezione=? AND Prenotazione.OraProiezione=?");
		$preparedQuery2->bind_param("is", $_POST["idproiez"], $_POST["orario"]);
		$preparedQuery2->execute();
		$result2 = $preparedQuery2->get_result();
				
		$db->disconnect();
		
		


		if (!empty($result1) && $result1->num_rows) {
			$listaPostiQuery = $result1->fetch_all(MYSQLI_ASSOC);
			
			$listaPostiStruct = array(); //array associativo nella forma [xy]=stato con x = fila posto y = numero posto
			$numTotFile = 1;
			$lastRow="a";
			foreach ($listaPostiQuery as $row) {  
				$fila = strtolower($row["Fila"]);
				$listaPostiStruct[$fila . $row["Numero"]] = 0;  //inizializzo tutti liberi
				
				//conto le file
				if($fila != $lastRow) 
					$numTotFile++;
				
				$lastRow = $fila; 
			}
			
			if(!empty($result2) && $result2->num_rows > 0){ //se ci sono posti occupati
				while($row = $result2->fetch_assoc()) {
					$listaPostiStruct[strtolower($row["Fila"]) . $row["Numero"]] = 1;  //posti occupati
				}
			}

			// trova la sequenza di posti consecutivi piu lunga per ogni categoria di posti
			$seqConsecMax = array(
				"davanti" => 0,
				"centrale" => 0,
				"dietro" => 0
			);
			
			$seqConsecEnd = array(
				"davanti" => "",
				"centrale" => "",
				"dietro" => ""
			);

			$lastRow="a";
			$curr_consec = 0;
			$max_consec = 0;
			$end = "";
			$numFila = 0;
			foreach($listaPostiStruct as $cod=>$stato) {
				$fila = substr($cod, 0, 1);
				$num = intval(substr($cod, 1));
				if($fila != $lastRow) {
					//cambio fila
					
					$max_consec = max($max_consec, $curr_consec);
					if ($numFila < 2) {
						$seqConsecMax["davanti"] = $max_consec;
						$seqConsecEnd["davanti"] = $end;
					} else if ($numFila > $numTotFile-3) {
						$seqConsecMax["dietro"] = $max_consec;
						$seqConsecEnd["dietro"] = $end;
					} else {					
						$seqConsecMax["centrale"] = $max_consec;
						$seqConsecEnd["centrale"] = $end;
					}
					$numFila++;
					$max_consec = 0;
					$curr_consec = 0;
				}
				
				if ($stato == 0) {
					$curr_consec++;
					$end = $lastRow . $num;
				} else {
					$max_consec = max($max_consec, $curr_consec);
					$curr_consec = 0;
				}
			
				$lastRow = $fila;
			}
			
			//testa ultima riga
			
			$max_consec = max($max_consec, $curr_consec);
			if ($numFila < 3) {
				$seqConsecMax["davanti"] = $max_consec;
				$seqConsecEnd["davanti"] = $end;
			} else if ($numFila > $numTotFile-3) {
				$seqConsecMax["dietro"] = $max_consec;
				$seqConsecEnd["dietro"] = $end;
			} else {					
				$seqConsecMax["centrale"] = $max_consec;
				$seqConsecEnd["centrale"] = $end;
			}
			
			
			$postiStr = "";
			if($postiVicini) {
				for($i = 0; $i < $totNumBiglietti; $i++) {
					$fila = substr($seqConsecEnd[$catPosti], 0, 1);
					$num = intval(substr($seqConsecEnd[$catPosti], 1));
					$postiStr .= $fila . $num - $i . ",";
				}
				//rimuovo virgola finale
				$postiStr = substr($postiStr, 0, -1);
			} else {
				//random
				foreach($listaPostiStruct as $cod => $stato) {
					if($stato)
						unset($listaPostiStruct[$cod]);
				}
				
				uksort($listaPostiStruct, function() { return (rand() > getrandmax() / 2); });
				
				foreach(array_slice($listaPostiStruct, 0, $totNumBiglietti) as $cod=>$s) {
					$fila = substr($cod, 0, 1);
					$num = intval(substr($cod, 1));
					$postiStr .= $fila . $num  . ",";
				}
				//rimuovo virgola finale
				$postiStr = substr($postiStr, 0, -1);
				
				
			}
			
			$idPrenotaz = prenotaPosti($postiStr, $username, $_POST["idproiez"], $_POST["orario"], $_POST["numSala"]);
			unset($listaPostiStruct);
			
			generaPaginaConferma($postiStr, $idPrenotaz, $totNumBiglietti);
			
		} else {
			unset($listaPostiStruct);
			header("Location: 500.php");
			die();
		}
		
	} else if ($_POST["modSelezPosti"] == "manual") {
		
		$idPrenotaz = prenotaPosti($_POST["seats"], $username, $_POST["idproiez"], $_POST["orario"], $_POST["numSala"]);
		generaPaginaConferma($_POST["seats"], $idPrenotaz, $totNumBiglietti);
	}
	
	
	function generaPaginaConferma($listaPostiFormat, $idPrenotaz, $totNumBiglietti) {
		//pagina conferma
		$document = file_get_contents("../html/template.html");
		$acquistoconferma_content = file_get_contents("../html/acquistoconferma_content.html");
		
		//SESSION
			if (isset($_SESSION["a"])) {
				$acquistoconferma_content = str_replace("<CLASS-WARNING>", "hide" ,$acquistoconferma_content);

				$document = str_replace("<LOGIN>", $_SESSION["a"], $document);
				$document = str_replace(
					"<LINK>",
					"./area_utenti.php?action=getProfile",
					$document
				);
			} else {
				$acquistoconferma_content = str_replace("<CLASS-WARNING>", "" ,$acquistoconferma_content);				
				$document = str_replace("<LOGIN>", "Login", $document);
				$document = str_replace(
					"<LINK>",
					"./area_utenti.php?action=login_page",
					$document
				);
			}
			
			$document = str_replace(
				"<PAGETITLE>",
				"Conferma acquisto biglietti per " . $_POST["titoloFilm"] . " - PNG Cinema",
				$document
			);
			$document = str_replace("<KEYWORDS>", "Acquisto, biglietti, ".$_POST["titoloFilm"], $document);
			$document = str_replace(
				"<DESCRIPTION>",
				"Pagina conferma acquisto: " . $_POST["titoloFilm"],
				$document
			);
			$document = str_replace(
				"<BREADCRUMB>",
				"Conferma acquisto",
				$document
			);
		
			$acquistoconferma_content = str_replace("<FILM-TITLE>", $_POST["titoloFilm"] ,$acquistoconferma_content);
			$acquistoconferma_content = str_replace("<NUM-BIGLIETTI>", $totNumBiglietti ,$acquistoconferma_content);
			$acquistoconferma_content = str_replace("<NUM-BIGLIETTI-INT-RID>", 
												"Intero: " . $_POST["numTicketIntero"] . " " .
												"Ridotto: " . $_POST["numTicketRidotto"],
												$acquistoconferma_content);
			
			$acquistoconferma_content = str_replace("<PREN-DATA-ORA>", $_POST["dataOraIta"] ,$acquistoconferma_content);
			$acquistoconferma_content = str_replace("<PREN-SALA>", $_POST["numSala"] ,$acquistoconferma_content);
			$acquistoconferma_content = str_replace("<POSTI-LISTA>", strtoupper($listaPostiFormat) ,$acquistoconferma_content);
			$acquistoconferma_content = str_replace("<COD-ACQ>", $idPrenotaz ,$acquistoconferma_content);
			
			$document = str_replace("<CONTENT>", $acquistoconferma_content, $document);
			echo $document;
		/*<meta name="robots" content="noindex" follow>*/
	}
?>