<?php
session_start();
require_once "utils/SingletonDB.php";
require_once "utils/generaSVG.php";
require_once "utils/generaData.php";
require_once "utils/mappaPosti.php";
require_once "utils/generaPagina.php";
require_once "utils/controlli.php";
require_once "utils/filtraTestoInglese.php";

//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta
$idproiez = -1;
$orario = "";

if (isset($_GET["idproiez"]) && is_numeric($_GET["idproiez"])) {
    $idproiez = $_GET["idproiez"];
} else {
    header("Location: 404.php");
    die();
}

$db = SingletonDB::getInstance();

$preparedQuery = $db
    ->getConnection()
    ->prepare(
        "SELECT Film.Titolo, Proiezione.Data, Proiezione.NumeroSala, Film.ID, Proiezione.Orario FROM Film INNER JOIN Proiezione ON Film.ID=Proiezione.IDFilm WHERE Proiezione.ID=? AND Proiezione.Data > current_date"
    );
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
	$orario = $dataFilm["Orario"];
	$postiOccupati = array();
    $statoPosti = mappaPosti($numeroSala, $idproiez, $orario, $postiOccupati);

	//costruisco la lista di posti occupati
	foreach ($statoPosti as $posto => $stato) {
		if ($stato==1)
			array_push($postiOccupati, $posto);
	}

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





    $prenotazione_content = file_get_contents(
        "../html/prenota.html"
    );

    $prenotazione_content = str_replace(
        "<FILM-TITLE>",
        filtraTestoInglese($dataFilm["Titolo"]),
        $prenotazione_content
    );

    $titolo = $dataFilm["Titolo"];
    $titolo = str_replace("{", "", $titolo);
    $titolo = str_replace("}", "", $titolo);

    $prenotazione_content = str_replace(
        "<FILM-TITLE-N>",
        $titolo,
        $prenotazione_content
    );

    $prenotazione_content = str_replace(
        "<PROJ-DATA>",
        $italianDate,
        $prenotazione_content
    );

    $prenotazione_content = str_replace(
        "<NUM-SALA>",
        $numeroSala,
        $prenotazione_content
    );
    $prenotazione_content = str_replace(
        "<ID-PROJ>",
        $idproiez,
        $prenotazione_content
    );
    $prenotazione_content = str_replace(
        "<TIME-PROJ>",
        $orario,
        $prenotazione_content
    );

    $prenotazione_content = str_replace(
        "<DISC-PRICE-FORMAT>",
        str_replace(".", ",", sprintf("%.2f", $prezzo["PrezzoRidotto"])),
        $prenotazione_content
    );
    $prenotazione_content = str_replace(
        "<FULL-PRICE-FORMAT>",
        str_replace(".", ",", sprintf("%.2f", $prezzo["PrezzoIntero"])),
        $prenotazione_content
    );
    $prenotazione_content = str_replace(
        "<DISC-PRICE>",
        $prezzo["PrezzoRidotto"],
        $prenotazione_content
    );
    $prenotazione_content = str_replace(
        "<FULL-PRICE>",
        $prezzo["PrezzoIntero"],
        $prenotazione_content
    );

    $prenotazione_content = str_replace(
        "<SVG-SEATS-MAP>",
        generateSVG($numeroSala, $idproiez, $orario),
        $prenotazione_content
    );

	 $prenotazione_content = str_replace(
        "<POSTI-OCCUPATI>",
        implode(",", $postiOccupati),
        $prenotazione_content
    );
	$prenotazione_content = str_replace(
        "<ID-FILM>",
        $dataFilm["ID"] ,
        $prenotazione_content
    );


    if (isset($_GET["err_server1"])) {
        $prenotazione_content = str_replace(
            "<ERRORE-SERVER>",
            '
			<p role="alert" class="warning"> <strong>Attenzione</strong>, hai tentato di prenotare più posti di quelli disponibili in totale.</p>
			',
            $prenotazione_content
        );
    } elseif (isset($_GET["err_server2"])) {
		pulisci($_GET["err_server2"]);
        $prenotazione_content = str_replace(
            "<ERRORE-SERVER>",
            '
			<p role="alert" class="warning"> <strong>Attenzione</strong>, il posto '. strtoupper($_GET["err_server2"]) .' selezionato risulta già occupato</p>',
            $prenotazione_content
        );
    } elseif (isset($_GET["err_server3"])) {
        $prenotazione_content = str_replace(
            "<ERRORE-SERVER>",
            '
			<p role="alert" class="warning"> <strong>Attenzione</strong>, non hai selezionato tutti i posti, per favore riprova.</p>
			',
            $prenotazione_content
        );
    } else {
        $prenotazione_content = str_replace(
            "<ERRORE-SERVER>",
            "",
            $prenotazione_content
        );
    }

    $title = "Acquista biglietti per " . $titolo . " - PNG Cinema";
    $keywords = "Acquista, biglietti, " . ($dataFilm["Titolo"]);
    $description = "Pagina acquisto biglietti per il film: " . ($dataFilm["Titolo"]);
    $breadcrumbs =
        '<a href="home.php">Home</a> / <a href="programmazione.php">Programmazione</a> / <a href="schedafilm.php?idfilm=' .
        $dataFilm["ID"] .
        '"' .
        ">Scheda Film: " .
        filtraTestoInglese($dataFilm["Titolo"]) .
        "</a>" .
        " / Acquisto biglietti";

    $jshead = '<script  src="../js/panzoom.min.js"> </script>';
    $jsbody = '<script src="../js/controlliAcquisto.js"> </script>';

    //GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
    echo GeneratePage(
        "Prenotazione",
        $prenotazione_content,
        $breadcrumbs,
        $title,
        $description,
        $keywords,
        $jshead,
        $jsbody
    );
} else {
    header("Location: 404.php");
    die();
}

?>
