<?php
session_start();

require_once "utils/SingletonDB.php";
require_once "utils/generaPagina.php";
require_once "utils/filtraTestoInglese.php";

//CheckSession($login_required, $admin_required)
CheckSession(false, false); //refresh della sessione se scaduta

$document = file_get_contents("../html/template.html");
$programmazione_content = file_get_contents(
    "../html/programmazione.html"
);

$db = SingletonDB::getInstance();
$filmsResult = $db
    ->getConnection()
    ->query("SELECT *, Film.ID as FilmID FROM Film INNER JOIN Proiezione ON Film.ID=Proiezione.IDFilm WHERE Data > current_date GROUP BY Film.ID ORDER BY DataUscita DESC");
$db->disconnect();

$cards = "";

if (!empty($filmsResult) && $filmsResult->num_rows > 0) {
    $card_prog_template = file_get_contents("../html/items/card_programmazione.html");

    while ($row = $filmsResult->fetch_assoc()) {

        $card_prog_item = $card_prog_template;

        $card_prog_item = str_replace(
            "<FILMDIRECTOR>",
            filtraTestoInglese($row["Registi"]),
            $card_prog_item
        );
        $card_prog_item = str_replace(
            "<FILMCAST>",
            filtraTestoInglese($row["Attori"]),
            $card_prog_item
        );

        $description = $row["Descrizione"];
        $description = substr($description, 0, 200);
        $description = filtraTestoInglese($description);
        $description = $description . "...";

        $card_prog_item = str_replace(
            "<FILMTITLE>",
            filtraTestoInglese($row["Titolo"]),
            $card_prog_item
        );
        $card_prog_item = str_replace(
            "<FILMGENRE>",
            $row["Genere"],
            $card_prog_item
        );
        $card_prog_item = str_replace(
            "<FILMLENGTH>",
            $row["Durata"] . "'",
            $card_prog_item
        );
        $card_prog_item = str_replace(
            "<FILMDESCRIPTION>",
            $description,
            $card_prog_item
        );

        $card_prog_item = str_replace(
            "<FILM-PAGE>",
            "schedafilm.php?idfilm=" . $row["FilmID"],
            $card_prog_item
        );

        $card_prog_item = str_replace(
            "<SRCIMG>",
            "../images/" . $row["SrcImg"],
            $card_prog_item
        );

        $card_prog_item = str_replace(
            "<ALTIMG>",
            "Locandina " . ($row["Titolo"]),
            $card_prog_item
        );

        $cards = $cards . $card_prog_item;
    }
} else {
    $cards = "Nessun film trovato.";
}

$programmazione_content = str_replace(
    "<CARDS-PROG>",
    $cards,
    $programmazione_content
);

$keywords =
    "programmazione, film, pngcinema, uscite, programma";
$description =
    "Pagina della programmazione di png cinema: è possibile consultare i film e le opere in programma nelle prossime settimane.";
$breadcrumbs = '<a href="home.php"><span lang="en">Home</span></a> / Programmazione';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "Programmazione",
    $programmazione_content,
    $breadcrumbs,
    "Programmazione - PNG Cinema",
    $description,
    $keywords,
    "",
    ""
);
?>
