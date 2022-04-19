<?php
session_start();

include "SingletonDB.php";
include "utils/createCastStr.php";
include "utils/generateItalianDate.php";
include "utils/pageGenerator.php";
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

    $preparedQuery2 = $db
        ->getConnection()
        ->prepare(
            "SELECT * FROM CastFilm JOIN Afferisce ON CastFilm.ID = Afferisce.IDCast WHERE Afferisce.IDFilm =?"
        );
    $preparedQuery2->bind_param("i", $_GET["idfilm"]);
    $preparedQuery2->execute();
    $result2 = $preparedQuery2->get_result();

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
        $result1->num_rows > 0 &&
        !empty($result2) &&
        $result2->num_rows > 0
    ) {
        //si assume che se c'è un film ha un cast e un direttore, per questo il controllo unico
        $dataFilm = $result1->fetch_assoc();
        $cast = createCastStr($result2);

        $schedafilm_content = file_get_contents(
            "../html/schedafilm_content.html"
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
            $cast["R"],
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-CAST>",
            $cast["A"],
            $schedafilm_content
        );
        $schedafilm_content = str_replace(
            "<FILM-DESC>",
            $dataFilm["Descrizione"],
            $schedafilm_content
        );

        if (!empty($result3) && $result3->num_rows) {
            $filmscreeningfield_template = file_get_contents(
                "../html/items/filmscreeningfield.html"
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
                        "SELECT * FROM Film INNER JOIN Proiezione ON (Film.ID = Proiezione.IDFilm) INNER JOIN Orario ON (Proiezione.ID = Orario.IDProiezione) WHERE Film.ID = ? AND Proiezione.Data = ?"
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
                        substr($orarioRow["Ora"], 0, -3) .
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
        //GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
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
