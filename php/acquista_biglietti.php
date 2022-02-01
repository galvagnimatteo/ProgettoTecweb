<?php
	session_start();
	include "SingletonDB.php";
	include "utils/prenotaPosti.php";
	include "utils/mappaPosti.php";
	
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
		
		list($postiLiberi, $seqConsecMax, $seqConsecEnd, $listaPostiQuery) = mappaPosti(
																$_POST["numSala"], 
																$_POST["idproiez"], 
																$_POST["orario"]
															);
			
			
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