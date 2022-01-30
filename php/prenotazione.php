<?php 
session_start();
	include "SingletonDB.php";
	include "utils/generateSVG.php";
	include "utils/generateItalianDate.php";
	
	$idproiez = -1;
	$orario = '';
	if(isset($_GET["idproiez"]) && is_numeric($_GET["idproiez"])) {
		$idproiez = $_GET["idproiez"]; 
	} else {
		header("Location: 404.php");
        die();
	}
	
	if(isset($_GET["orario"]) && strtotime($_GET["orario"]) !== false) {
		$orario = $_GET["orario"]; 
	} else {
		header("Location: 404.php");
        die();
	}
	
	$db = SingletonDB::getInstance();
	
	$preparedQuery = $db
        ->getConnection()
        ->prepare("SELECT Film.Titolo, Proiezione.Data, Proiezione.NumeroSala FROM Film INNER JOIN Proiezione ON Film.ID=Proiezione.IDFilm WHERE Proiezione.ID=?");
    $preparedQuery->bind_param("i", $idproiez);
    $preparedQuery->execute();
    $result1 = $preparedQuery->get_result();
	$db->disconnect();
	
	if (!empty($result1) && $result1->num_rows > 0) {
		//risultato unico
		$dataFilm = $result1->fetch_assoc();
		
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
                /*$dataFilm["ID"]*/ "" .
                '">Scheda Film: ' .
                $dataFilm["Titolo"] .
                "</a>",
            $document
        );
		
		$prenotazione_content = str_replace("<FILM-TITLE>", $dataFilm["Titolo"], $prenotazione_content);
		$prenotazione_content = str_replace("<PROJ-DATA>", 
		generateItalianDate(date_timestamp_get(date_create($dataFilm["Data"]))), $prenotazione_content);
		$prenotazione_content = str_replace("<ID-PROJ>", $idproiez, $prenotazione_content);
		$prenotazione_content = str_replace("<TIME-PROJ>", $orario, $prenotazione_content);
		
		$prenotazione_content = str_replace("<DISC-PRICE-FORMAT>", "8,00", $prenotazione_content);
		$prenotazione_content = str_replace("<FULL-PRICE-FORMAT>", "10,00" , $prenotazione_content);
		$prenotazione_content = str_replace("<DISC-PRICE>", 8.00, $prenotazione_content);
		$prenotazione_content = str_replace("<FULL-PRICE>", 10.00, $prenotazione_content);
		
		$prenotazione_content = str_replace("<SVG-SEATS-MAP>", generateSVG($dataFilm["NumeroSala"], $idproiez, $orario), $prenotazione_content);
		
		$document = str_replace("<CONTENT>", $prenotazione_content, $document);
		echo $document;
	} else {
		header("Location: 404.php");
        die();
	}
	

?>