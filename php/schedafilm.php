<?php
session_start();

require_once "utils/SingletonDB.php";
require_once "utils/generaPagina.php";
require_once "utils/generaData.php";
require_once "utils/filtraTestoInglese.php";

//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta

if (isset($_GET["idfilm"]) && is_numeric($_GET["idfilm"])) {
    $db = SingletonDB::getInstance();

    $preparedQuery = $db
        ->getConnection()
        ->prepare("SELECT * FROM Film WHERE Film.ID=?");
    $preparedQuery->bind_param("i", $_GET["idfilm"]);
    $preparedQuery->execute();
    $result1 = $preparedQuery->get_result();

    $preparedQuery3 = $db
        ->getConnection()
        ->prepare(
            "SELECT Data FROM Proiezione INNER JOIN Film ON Proiezione.IDFilm = Film.ID WHERE Film.ID=? AND Data > 'date(\"Y-m-d\")' GROUP BY Data ORDER BY Data "
        );
    $preparedQuery3->bind_param("i", $_GET["idfilm"]);
    $preparedQuery3->execute();
    $result3 = $preparedQuery3->get_result();

    $db->disconnect();

    if (
        !empty($result1) &&
        $result1->num_rows > 0
    ) {

        $dataFilm = $result1->fetch_assoc();

        $schedafilm_content = file_get_contents(
            "../html/scheda_film.html"
        );

        $schedafilm_content = str_replace(
            "<FILM-TITLE>",
            filtraTestoInglese($dataFilm["Titolo"]),
            $schedafilm_content
        );

        $titolo = $dataFilm["Titolo"];

        $titolo = str_replace("{", "", $titolo);
        $titolo = str_replace("}", "", $titolo);


        $schedafilm_content = str_replace(
            "<FILM-IMG>",
            '<img src=\'' .
                "../images/" .
                $dataFilm["SrcImg"] .
                ' \' alt=\'' .
                "Locandina " .
                $titolo .
                '\'/>',
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-GENRE>",
            $dataFilm["Genere"],
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<RUNNING-TIME>",
            $dataFilm["Durata"] . " min",
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-DIRECTOR>",
            filtraTestoInglese($dataFilm["Registi"]),
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-CAST>",
            filtraTestoInglese($dataFilm["Attori"]),
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-DESC>",
            filtraTestoInglese($dataFilm["Descrizione"]),
            $schedafilm_content
        );

        if (!empty($result3) && $result3->num_rows) {
            $filmscreeningfield_template = file_get_contents(
                "../html/items/campo_proiezione.html"
            );
            $filmscreeningfields = "";

            while ($row = $result3->fetch_assoc()) {
                $filmscreeningfield = $filmscreeningfield_template;

                $filmscreeningfield = str_replace(
                    "<DATA>",
                    generateItalianDate($row["Data"]),
                    $filmscreeningfield
                );

                $db->connect();
                $preparedQuery4 = $db
                    ->getConnection()
                    ->prepare(
                        "SELECT *, Proiezione.ID as IDProiezione FROM Film INNER JOIN Proiezione ON (Film.ID = Proiezione.IDFilm) WHERE Film.ID = ? AND Proiezione.Data = ?"
                    );
                $preparedQuery4->bind_param(
                    "is",
                    $_GET["idfilm"],
                    $row["Data"]
                );
                $preparedQuery4->execute();
                $result4 = $preparedQuery4->get_result();
                $db->disconnect();

                $hour_fields = "";

                while ($orarioRow = $result4->fetch_assoc()) {
/*
                    $hour_field =
                        '<input type="hidden" name="idproiez" value="' .
                        $orarioRow["IDProiezione"] .
                        '"/>';
                    $hour_fields .= $hour_field;

                    <button name="subject" type="submit" value="fav_HTML">HTML</button>

*/
                    $hour_field =
                        '<li><a href="../php/prenotazione.php?idproiez=' .
                        $orarioRow["IDProiezione"] .
                        ' >'.
                        substr($orarioRow["Orario"], 0, -3) .
                        "</a></li>";
                    $hour_fields .= $hour_field;
                }

                $filmscreeningfield = str_replace(
                    "<HOURS-INPUTS>",
                    $hour_fields,
                    $filmscreeningfield
                );

                $filmscreeningfields .= $filmscreeningfield;
            }

            $schedafilm_content = str_replace(
                "<SCREENING-FIELDS>",
                $filmscreeningfields,
                $schedafilm_content
            );
        } //else nessun problema, il film non ha programmazioni in corso
        $title = $dataFilm["Titolo"];
        $title = str_replace("{", "", $title);
        $title = str_replace("}", "", $title);
        $keywords = "scheda, film, " . $title . ", genere, regia, descrizione, durata, cast, orari, programmazione";
        $description = "Scheda informativa del film " . $title . " in proiezione a png cinema: è possibile leggere descrizione e informazioni varie sul film nonchè visualizzare date e orari di proiezione";
        $breadcrumbs =
            '<a href="home.php"><span lang="en">Home</span></a> / <a href="programmazione.php">Programmazione</a> / ' .
            "" .
            filtraTestoInglese($dataFilm["Titolo"]);
        echo GeneratePage(
            "scheda film",
            $schedafilm_content,
            $breadcrumbs,
            $title . " - PNG Cinema",
            $description,
            $keywords,
            "",
            ""
        );
    } else {
        header("Location: 404.php");
        die();
    }
} else {
    header("Location: 404.php");
    die();
}

?>
