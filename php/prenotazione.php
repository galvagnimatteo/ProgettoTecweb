<?php 
session_start();
	include "SingletonDB.php";
	include "utils/generateSVG.php";
	include "utils/generateItalianDate.php";
	include "utils/mappaPosti.php";
	
	$idproiez = -1;
	$orario = '';
	if(isset($_GET["idproiez"]) && is_numeric($_GET["idproiez"])) {
		$idproiez = $_GET["idproiez"]; 
	} else {
		header("Location: 404.php");
        die();
	}
	
	if(isset($_GET["orario"]) && strtotime($_GET["orario"])) {
		$orario = $_GET["orario"]; 
	} else {
		header("Location: 404.php");
        die();
	}
	
	$db = SingletonDB::getInstance();
	
	$preparedQuery = $db
        ->getConnection()
        ->prepare("SELECT Film.Titolo, Proiezione.Data, Proiezione.NumeroSala, Film.ID FROM Film INNER JOIN Proiezione ON Film.ID=Proiezione.IDFilm WHERE Proiezione.ID=?");
    $preparedQuery->bind_param("i", $idproiez);
    $preparedQuery->execute();
    $result1 = $preparedQuery->get_result();
	
	$db->disconnect();
		
	
	
	if (!empty($result1) && $result1->num_rows > 0) {
		//risultato unico
		$dataFilm = $result1->fetch_assoc();
		$numeroSala = $dataFilm["NumeroSala"];
		$italianDate = generateItalianDate($dataFilm["Data"]);
		
		$temp = explode(" ", $italianDate);
		$giorno = $temp[0];
		
		list($postiLiberi, $seqConsecMax, $seqConsecEnd, $listaPostiQuery) = mappaPosti($numeroSala, $idproiez, $orario);

		unset($listaPostiQuery);
		
		$db->connect();
		
		$preparedQuery2 = $db
				->getConnection()
				->prepare("SELECT * FROM Prezzi WHERE Prezzi.Giorno=?");
		$preparedQuery2->bind_param("s", $giorno);
		$preparedQuery2->execute();
		$result2 = $preparedQuery2->get_result();
		
		$db->disconnect();
		
		
		$prezzo;
	
		if (!empty($result2) && $result2->num_rows > 0) {
			$prezzo = $result2->fetch_assoc();
			
		} else {
			header("Location: 500.php");
			die();
		}
		
		$document = file_get_contents("../html/template.html");
		$prenotazione_content = file_get_contents("../html/prenotazione_content.html");
		
		//SESSION
		if (isset($_SESSION["a"])) {
			$document = str_replace("<LOGIN>", $_SESSION["a"], $document);
			$document = str_replace(
				"<LINK>",
				"./area_utenti.php?action=getProfile",
				$document
			);
		} else {
			$document = str_replace("<LOGIN>", "Login", $document);
			$document = str_replace(
				"<LINK>",
				"./area_utenti.php?action=login_page",
				$document
			);
		}
		
		if(isset($_SESSION["admin"])&&$_SESSION["admin"]){
			$document = str_replace("<ADMIN>","<li><a href='admin.php'>Amministrazione</a></li>",$document);
		}
		else{
			$document = str_replace("<ADMIN>","",$document);
		}
		
		$document = str_replace(
            "<PAGETITLE>",
            "Acquista biglietti per " . $dataFilm["Titolo"] . " - PNG Cinema",
            $document
        );
        $document = str_replace("<KEYWORDS>", "Acquista, biglietti, ".$dataFilm["Titolo"], $document);
        $document = str_replace(
            "<DESCRIPTION>",
            "Scheda informativa del film: " . $dataFilm["Titolo"],
            $document
        );
        $document = str_replace(
            "<BREADCRUMB>",
            '<a href="home.php">Home</a> / <a href="programmazione.php">Programmazione</a> / <a href="schedafilm.php?idfilm=' .
                $dataFilm["ID"] . '"' .
                '>Scheda Film: ' .
                $dataFilm["Titolo"] .
                "</a>" . ' / Acquisto biglietti' ,
            $document
        );
		
		$document = str_replace("<JAVASCRIPT-HEAD>", '<script  src="../js/panzoom.min.js"> </script>', $document);
		$document = str_replace("<JAVASCRIPT-BODY>", '<script src="../js/controlliAcquisto.js"> </script>', $document);
		
		$prenotazione_content = str_replace("<FILM-TITLE>", $dataFilm["Titolo"], $prenotazione_content);
		$prenotazione_content = str_replace("<PROJ-DATA>", $italianDate, $prenotazione_content);
		
		$prenotazione_content = str_replace("<NUM-SALA>", $numeroSala, $prenotazione_content);				
		$prenotazione_content = str_replace("<ID-PROJ>", $idproiez, $prenotazione_content);
		$prenotazione_content = str_replace("<TIME-PROJ>", $orario, $prenotazione_content);
		
		$prenotazione_content = str_replace("<DISC-PRICE-FORMAT>", str_replace(".", ",",sprintf("%.2f", $prezzo["PrezzoRidotto"])), $prenotazione_content);
		$prenotazione_content = str_replace("<FULL-PRICE-FORMAT>", str_replace(".", ",",sprintf("%.2f", $prezzo["PrezzoIntero"]) ), $prenotazione_content);
		$prenotazione_content = str_replace("<DISC-PRICE>",$prezzo["PrezzoRidotto"], $prenotazione_content);
		$prenotazione_content = str_replace("<FULL-PRICE>", $prezzo["PrezzoIntero"], $prenotazione_content);
		
		$prenotazione_content = str_replace("<SVG-SEATS-MAP>", generateSVG($numeroSala, $idproiez, $orario), $prenotazione_content);
		
		
		$prenotazione_content = str_replace("<POSTI-LIB>", $postiLiberi, $prenotazione_content);
		$prenotazione_content = str_replace("<MAX-SEQ-DV>", $seqConsecMax["davanti"], $prenotazione_content);	
		$prenotazione_content = str_replace("<MAX-SEQ-DT>", $seqConsecMax["dietro"], $prenotazione_content);
		$prenotazione_content = str_replace("<MAX-SEQ-CE>", $seqConsecMax["centrale"], $prenotazione_content);
		
		
		if(isset($_GET["err_server1"])) {
			$prenotazione_content = str_replace("<ERRORE-SERVER>", '
			<p role="alert" class="warning"> <strong>Attenzione</strong>, ci sono stati alcuni errori con l\'ultima prenotazione, verifica di non aver selezionato più di 4 posti.</p>
			' , $prenotazione_content);
			
		} else if (isset($_GET["err_server2"])) {
			$prenotazione_content = str_replace("<ERRORE-SERVER>", '
			<p role="alert" class="warning"> <strong>Attenzione</strong>, alcuni dei posti che hai selezionato sono già stati occupati oppure non ci sono più posti liberi nella categoria selezionata, per favore riprova.</p>
			' , $prenotazione_content);
		} else if (isset($_GET["err_server3"])) {
			$prenotazione_content = str_replace("<ERRORE-SERVER>", '
			<p role="alert" class="warning"> <strong>Attenzione</strong>, non hai selezionato tutti i posti, per favore riprova.</p>
			' , $prenotazione_content);
		} else {
			$prenotazione_content = str_replace("<ERRORE-SERVER>", "" , $prenotazione_content);
		}
		
		
		$document = str_replace("<CONTENT>", $prenotazione_content, $document);
		echo $document;
	} else {
		header("Location: 404.php");
        die();
	}
	

?>