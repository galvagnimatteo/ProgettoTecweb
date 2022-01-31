<?php
	
	include "SingletonDB.php";
	include "utils/prenotaPosti.php";
	
	if (!isset($_POST["numTicketIntero"], $_POST["numTicketRidotto"], $_POST["catPosti"], 
			   $_POST["modSelezPosti"], $_POST["idproiez"], $_POST["orario"], $_POST["seats"], $_POST["numSala"])) 
	{
		
		header("Location: 500.php");
		die();
	}
	
	$totBiglietti = intval($_POST["numTicketIntero"]) + intval($_POST["numTicketRidotto"]);
	
	if ($_POST["modSelezPosti"]== "auto") {
		
		/*$postiVicini = isset($_POST["postiVicini"]);
		
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
		
		$statoPostiMatrix = array();


		if (!empty($result1) && $result1->num_rows) {
			$listaPostiQuery = $result1->fetch_all(MYSQLI_ASSOC);
			
			$listaPostiStruct = array(); //array associativo nella forma [xy]=stato con x = fila posto y = numero posto
			
			foreach ($listaPostiQuery as $row) {  
				$listaPostiStruct[$row["Fila"] . $row["Numero"]] = 0;  //inizializzo tutti liberi
			}
			
			if(!empty($result2) && $result2->num_rows > 0){ //se ci sono posti occupati
				while($row = $result2->fetch_assoc()) {
					$listaPostiStruct[$row["Fila"] . $row["Numero"]] = 1;  //posti occupati
				}
			}
			
			
			$statoPostiMatrix = array();
			$listaPostiTemp = array();
			$lastRow="a";
			$numeroFile = 1;
			foreach ($listaPostiQuery as $row) {

				if($row["Fila"] == $lastRow) {
					$lastRow = strtolower($row["Fila"]);
					$listaPostiTemp[] = array($listaPostiStruct[$row["Fila"] . $row["Numero"]],
											  $row["Fila"], $row["Numero"]); //append
					
					//echo $listaPostiStruct[$row["Fila"] . $row["Numero"]] . " " .
					//						  " " . $row["Fila"] . " " . $row["Numero"] ."\n" ;
				} else {
					$numeroFile++;
					//cambio riga
					$statoPostiMatrix[] = $listaPostiTemp;
					$listaPostiTemp = array();
					
					$lastRow = strtolower($row["Fila"]);
					$listaPostiTemp[] = array($listaPostiStruct[$row["Fila"] . $row["Numero"]],
											  $row["Fila"], $row["Numero"]); //append
											  
					//echo $listaPostiStruct[$row["Fila"] . $row["Numero"]] . " " .
					//						  " " . $row["Fila"] . " " . $row["Numero"] . "<br/>";
				}
				
				
			}
			
			echo $statoPostiMatrix[0][0];
			
			// trova la sequenza di posti consecutivi piu lunga per ogni categoria di posti
			/*$curr_consec = 0;
			$max_consec = 0;
			$start = "";
			$seqConsecMax = array(
				"davanti" => 0,
				"cantrale" => 0,
				"dietro" => 0
			);
			
			$seqConsecStart = array(
				"davanti" => "",
				"cantrale" => "",
				"dietro" => ""
			);
			
			foreach($statoPostiMatrix as $i) {
				
				if ($i < 2) {
					$seqConsecMax["davanti"] = $max_consec;
					$seqConsecMax["davanti"] = $start;
					$max_consec = 0;
				} else if ($i > $numeroFile-2) {
					$seqConsecMax["dietro"] = $max_consec;
					$seqConsecMax["dietro"] = $start;
					$max_consec = 0;
				} else {
					$seqConsecMax["centrale"] = $max_consec;
					$seqConsecMax["centrale"] = $start;
					$max_consec = 0;
				}
				
				$start = $statoPostiMatrix[$i][0][1] . $statoPostiMatrix[$i][0][2];
				foreach($i as  $j) {
					
					if($statoPostiMatrix[$i][$j][0] == 0) 
						$curr_consec++;
					else {
						$max_consec = max($max_consec, $curr_consec);
						$curr_consec = 0;
					}
				}
			}	
			
			echo $seqConsecMax["davanti"];*/
			/*
			
			 int ans = -1;

			 for (int i = 0; i < row; ++i)

			 {

			 for (int j = 1; j < col; ++j)

			 {

			 if(a[i][j] == 1)

			 d[i][j] = 1 + d[i - 1][j]; 

			 ans = max(ans, d[i][j]);

			 }

			 }
			
			*/
			
			/*if ($catPosti == "davanti") {
				
				foreach($statoPosti as $fila) {
					
					foreach($fila as $numPosto => $stato) {
							
						
							
					}
					
				}
				
			} */
			
			/*unset($statoPosti);*/
		} else {
			
			echo $result1->num_rows;
			/*unset($statoPosti);*/
			header("Location: 500.php");
			die();
		}
		
	} else if ($_POST["modSelezPosti"] == "manual") {
		
		prenotaPosti($_POST["seats"], "username4", $totBiglietti, $_POST["idproiez"], $_POST["orario"], $_POST["numSala"]);
		
		echo "ok";
	}
	
	
	
	
	

?>