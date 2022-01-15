<?php

    include 'SingletonDB.php';
    include 'mostra_errori.php';

    $document = file_get_contents('../html/template.html');
    $home_content = file_get_contents('../html/home_content.html');
    $cards = "";

    $db = SingletonDB::getInstance();
    $resultFilms = $db->getConnection()->query("SELECT * FROM Film ORDER BY DataUscita LIMIT 12");

    if(!empty($resultFilms) && $resultFilms->num_rows > 0){

        $card_home_template = file_get_contents('../html/items/card-home.html');

        while($row = $resultFilms->fetch_assoc()) {

            $preparedQuery = $db->getConnection()->prepare("SELECT * FROM CastFilm INNER JOIN Afferisce on (CastFilm.ID = Afferisce.IDCast) INNER JOIN Film on (Afferisce.IDFilm = Film.ID) WHERE Film.ID = ?");
            $preparedQuery->bind_param('i', $row["ID"]);
            $preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();

            $preparedQuery->close();

            $card_home_item = $card_home_template;

            $card_home_item = str_replace('<FILMTITLE>', $row["Titolo"], $card_home_item);

            $director = "";
            $cast = "";

            while($rowCast = $resultCast->fetch_assoc()) {

                if($rowCast["Ruolo"] == "R"){

                    if($director == ""){

                        $director = $rowCast["Nome"] . " " . $rowCast["Cognome"];

                    }else{

                        $director = $director . ", " . $rowCast["Nome"] . " " . $rowCast["Cognome"];

                    }

                }else{

                    if($cast == ""){

                        $cast = $rowCast["Nome"] . " " . $rowCast["Cognome"];

                    }else{

                        $cast = $cast . ", " . $rowCast["Nome"] . " " . $rowCast["Cognome"];

                    }
                }

            }

            $card_home_item = str_replace('<FILMDIRECTOR>', $director, $card_home_item);
            $card_home_item = str_replace('<FILMCAST>', $cast, $card_home_item);

            $cards = $cards . $card_home_item;

        }

        $db->disconnect();

    }else{

        $cards = "Nessun film trovato."; //TODO display errore

    }

    $home_content = str_replace('<CARDS-HOME>', $cards, $home_content);

    $document = str_replace('<CONTENT>', $home_content, $document);

    echo($document);

?>