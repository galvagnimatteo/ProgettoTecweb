<?php
session_start();
require_once "utils/SingletonDB.php";
require_once "utils/prenotaPosti.php";
require_once "utils/mappaPosti.php";
require_once "utils/generaPagina.php";
require_once "utils/controlli.php";
require_once "utils/filtraTestoInglese.php";

//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta

if (
    !isset(
        $_POST["numTicketIntero"],
        $_POST["numTicketRidotto"],
        $_POST["modSelezPosti"],
        $_POST["idproiez"],
        $_POST["orario"],
        $_POST["seats"],
        $_POST["numSala"],
        $_POST["titoloFilm"],
        $_POST["dataIta"],
        $_POST["pint"],
        $_POST["prid"],
		$_POST["IDFilm"]
    )
) {
    header("Location: 500.php?1");
    die();
}

pulisci($_POST["orario"]);
pulisci($_POST["modSelezPosti"]);
pulisci($_POST["seats"]);
pulisci($_POST["dataIta"]);
pulisci($_POST["titoloFilm"]);

//verifiche
if (
    !(
        is_numeric($_POST["numTicketIntero"]) &&
        is_numeric($_POST["numTicketRidotto"]) &&
        is_numeric($_POST["idproiez"]) &&
        is_numeric($_POST["numSala"]) &&
        is_numeric($_POST["pint"]) &&
		is_numeric($_POST["IDFilm"]) &&
        is_numeric($_POST["prid"])
    )
) {

    header("Location: 500.php?2");
    die();
}

$username = ""; //se resta '' inserisce null e l'utente è un ospite
if (isset($_SESSION["a"])) {
    $username = $_SESSION["a"];
}

$totNumBiglietti =
    intval($_POST["numTicketIntero"]) + intval($_POST["numTicketRidotto"]);

$postiver = explode(",", $_POST["seats"]);




$totPostiLiberi = -1;
$statoPosti = mappaPosti($_POST["numSala"], $_POST["idproiez"], $_POST["orario"], $totPostiLiberi);


if ($totNumBiglietti > $totPostiLiberi || count($postiver) > $totPostiLiberi) {
    header(
        "Location: prenotazione.php?idproiez=" .
            $_POST["idproiez"] .
            "&err_server1=1"
    );
    die();
}

$postoOccupato = NULL;
foreach ($postiver as $posto) {
	if ($statoPosti[$posto] == 1) { //se un posto è occupato
		$postoOccupato = $posto;
		break;
	}
}

if ($postoOccupato != NULL) {
	header(
		"Location: prenotazione.php?idproiez=" .
			$_POST["idproiez"] .
			"&err_server2=". $postoOccupato
	);
	die();
}


if (count($postiver) < $totNumBiglietti) {
	header(
		"Location: prenotazione.php?idproiez=" .
			$_POST["idproiez"] .
			"&err_server3=1"
	);
	die();
}

$idPrenotaz = prenotaPosti(
	$_POST["seats"],
	$username,
	$_POST["idproiez"],
	$_POST["numSala"]
);

unset($statoPosti);

if ($idPrenotaz != -1) {
	generaPaginaConferma($_POST["seats"], $idPrenotaz, $totNumBiglietti);

} else {
	header("Location: 500.php");
	die();
}






function generaPaginaConferma($listaPostiFormat, $idPrenotaz, $totNumBiglietti)
{
    //pagina conferma
    $acquistoconferma_content = file_get_contents(
        "../html/conferma_acquisto.html"
    );



    $acquistoconferma_content = str_replace(
        "<FILM-TITLE>",
        filtraTestoInglese($_POST["titoloFilm"]),
        $acquistoconferma_content
    );
    $acquistoconferma_content = str_replace(
        "<NUM-BIGLIETTI>",
        $totNumBiglietti,
        $acquistoconferma_content
    );
    $acquistoconferma_content = str_replace(
        "<NUM-BIGLIETTI-INT-RID>",
        "Intero: " .
            $_POST["numTicketIntero"] .
            " " .
            "Ridotto: " .
            $_POST["numTicketRidotto"],
        $acquistoconferma_content
    );

    $acquistoconferma_content = str_replace(
        "<PREN-DATA-ORA>",
        $_POST["dataIta"] . ", " . substr($_POST["orario"], 0, -3),
        $acquistoconferma_content
    );
    $acquistoconferma_content = str_replace(
        "<PREN-SALA>",
        $_POST["numSala"],
        $acquistoconferma_content
    );
    $acquistoconferma_content = str_replace(
        "<POSTI-LISTA>",
        strtoupper($listaPostiFormat),
        $acquistoconferma_content
    );
    $acquistoconferma_content = str_replace(
        "<COD-ACQ>",
        $idPrenotaz,
        $acquistoconferma_content
    );
    $acquistoconferma_content = str_replace(
        "<TOT-SPESO>",
        floatval($_POST["pint"]) * intval($_POST["numTicketIntero"]) +
            floatval($_POST["prid"]) * intval($_POST["numTicketRidotto"]),
        $acquistoconferma_content
    );
	$acquistoconferma_content = str_replace(
        "<CLASS-WARNING>",
		(isset($_SESSION["a"])) ? "hide" : "",
        $acquistoconferma_content
    );

    $titolo = $_POST["titoloFilm"];
    $titolo = str_replace("{", "", $titolo);
    $titolo = str_replace("}", "", $titolo);


    $title = "Conferma acquisto - PNG Cinema";
    $keywords = "Acquisto, biglietti, " . $titolo . "conferma";
    $description =
        "pagina di conferma acquisto biglietti per il film " . $titolo . "di png cinema: stampa questa pagina per salvare la prenotazione o presenta il codice prenotazione direttamente al cinema";
    $breadcrumbs =
        '<a href="home.php"><span lang="en">Home</span></a> / <a href="programmazione.php">Programmazione</a> / <a href="schedafilm.php?idfilm=' .
        $_POST["IDFilm"] .
        '"' .
        ">Scheda Film: " .
        filtraTestoInglese($_POST["titoloFilm"]) .
        "</a>" .
        ' / <a href="prenotazione.php?idproiez=' .
		$_POST["idproiez"] . '"' .
		'>Acquisto biglietti</a> / Conferma acquisto';

	$jshead =
        '<meta name="robots" content="noindex" /> ' . 
        ' <script src="../js/promptonclose.js"></script>';
    //GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
    echo GeneratePage(
        "Conferma",
        $acquistoconferma_content,
        $breadcrumbs,
        $title,
        $description,
        $keywords,
        $jshead,
        ""
    );
}
?>
