<?php
session_start();

require_once "utils/SingletonDB.php";
require_once "utils/generaPagina.php";
require_once "utils/generaData.php";

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
            "SELECT Data, Proiezione.ID as IDProiezione FROM Proiezione INNER JOIN Film ON Proiezione.IDFilm = Film.ID WHERE Film.ID=? ORDER BY Data"
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
            $dataFilm["Titolo"],
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-IMG>",
            '<img src=\'' .
                "../images/" .
                $dataFilm["SrcImg"] .
                ' \' alt=\'' .
                "Locandina " .
                $dataFilm["Titolo"] .
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
            $dataFilm["Registi"],
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-CAST>",
            $dataFilm["Attori"],
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-DESC>",
            $dataFilm["Descrizione"],
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

                $filmscreeningfield = str_replace(
                    "<IDPROIEZ-HIDDEN>",
                    '<input type="hidden" name="idproiez" value="' .
                        $row["IDProiezione"] .
                        '" />',
                    $filmscreeningfield
                );

                $db->connect();
                $preparedQuery4 = $db
                    ->getConnection()
                    ->prepare(
                        "SELECT * FROM Film INNER JOIN Proiezione ON (Film.ID = Proiezione.IDFilm) WHERE Film.ID = ? AND Proiezione.Data = ?"
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
                    //si assume che se c'è una data di proiezione ci siano anche degli orari quindi nessun controllo necessario

                    $hour_field =
                        '<input type="submit" name="orario" value="' .
                        substr($orarioRow["Orario"], 0, -3) .
                        '"/>';
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
        $title = $dataFilm["Titolo"] . " - PNG Cinema";
        $keywords = $dataFilm["Titolo"];
        $description = "Scheda informativa del film: " . $dataFilm["Titolo"];
        $breadcrumbs =
            '<a href="home.php">Home</a> / <a href="programmazione.php">Programmazione</a> / ' .
            "Scheda Film: " .
            $dataFilm["Titolo"];
        echo GeneratePage(
            "login",
            $schedafilm_content,
            $breadcrumbs,
            $dataFilm["Titolo"] . " - PNG Cinema",
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
