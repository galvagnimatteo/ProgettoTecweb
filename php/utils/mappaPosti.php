<?php

function mappaPosti($numSala, $idproiez, $orario)
{
    //mappa posti
    $db = SingletonDB::getInstance();
    $db->connect();
    $preparedQuery = $db //post totali
        ->getConnection()
        ->prepare(
            "SELECT Posto.Fila, Posto.Numero FROM Posto INNER JOIN Sala ON Sala.Numero = Posto.NumeroSala WHERE Sala.Numero = ? ORDER BY Posto.Fila, Posto.Numero"
        );
    $preparedQuery->bind_param("i", $numSala);
    $preparedQuery->execute();
    $result1 = $preparedQuery->get_result();

    $preparedQuery2 = $db //posti occupati
        ->getConnection()
        ->prepare(
            "SELECT NumeroPosto as Numero, FilaPosto as Fila FROM Prenotazione INNER JOIN Partecipa ON Prenotazione.ID=Partecipa.IDPrenotazione WHERE Prenotazione.IDProiezione=? AND Prenotazione.OraProiezione=?"
        );
    $preparedQuery2->bind_param("is", $idproiez, $orario);
    $preparedQuery2->execute();
    $result2 = $preparedQuery2->get_result();

    $db->disconnect();

    $postiLiberi = $result1->num_rows - $result2->num_rows;

    if (!empty($result1) && $result1->num_rows) {
        $listaPostiQuery = $result1->fetch_all(MYSQLI_ASSOC);

        $listaPostiStruct = []; //array associativo nella forma [xy]=stato con x = fila posto y = numero posto
        $numTotFile = 1;
        $lastRow = "a";
        foreach ($listaPostiQuery as $row) {
            $fila = strtolower($row["Fila"]);
            $listaPostiStruct[$fila . $row["Numero"]] = 0; //inizializzo tutti liberi

            //conto le file
            if ($fila != $lastRow) {
                $numTotFile++;
            }

            $lastRow = $fila;
        }

        if (!empty($result2) && $result2->num_rows > 0) {
            //se ci sono posti occupati
            while ($row = $result2->fetch_assoc()) {
                $listaPostiStruct[
                    strtolower($row["Fila"]) . $row["Numero"]
                ] = 1; //posti occupati
            }
        }

        // trova la sequenza di posti consecutivi piu lunga per ogni categoria di posti
        $seqConsecMax = [
            "davanti" => -1,
            "centrale" => -1,
            "dietro" => -1,
        ];

        $seqConsecEnd = [
            "davanti" => "",
            "centrale" => "",
            "dietro" => "",
        ];

        $lastRow = "a";
        $curr_consec = 0;
        $max_consec = 0;
        $end = "";
        $numFila = 0;
        foreach ($listaPostiStruct as $cod => $stato) {
            $fila = substr($cod, 0, 1);
            $num = intval(substr($cod, 1));
            if ($fila != $lastRow) {
                //cambio fila

                $max_consec = max($max_consec, $curr_consec);
                if ($numFila < 2) {
                    if ($seqConsecMax["davanti"] < $max_consec) {
                        $seqConsecMax["davanti"] = $max_consec;
                        $seqConsecEnd["davanti"] = $end;
                    }
                } elseif ($numFila > $numTotFile - 3) {
                    if ($seqConsecMax["dietro"] < $max_consec) {
                        $seqConsecMax["dietro"] = $max_consec;
                        $seqConsecEnd["dietro"] = $end;
                    }
                } else {
                    if ($seqConsecMax["centrale"] < $max_consec) {
                        $seqConsecMax["centrale"] = $max_consec;
                        $seqConsecEnd["centrale"] = $end;
                    }
                }
                $numFila++;
                $max_consec = 0;
                $curr_consec = 0;
            }

            if ($stato == 0) {
                $curr_consec++;
                $end = $lastRow . $num;
            } else {
                $max_consec = max($max_consec, $curr_consec);
                $curr_consec = 0;
            }

            $lastRow = $fila;
        }

        //testa ultima riga

        $max_consec = max($max_consec, $curr_consec);
        if ($numFila < 3) {
            if ($seqConsecMax["davanti"] < $max_consec) {
                $seqConsecMax["davanti"] = $max_consec;
                $seqConsecEnd["davanti"] = $end;
            }
        } elseif ($numFila > $numTotFile - 3) {
            if ($seqConsecMax["dietro"] < $max_consec) {
                $seqConsecMax["dietro"] = $max_consec;
                $seqConsecEnd["dietro"] = $end;
            }
        } else {
            if ($seqConsecMax["centrale"] < $max_consec) {
                $seqConsecMax["centrale"] = $max_consec;
                $seqConsecEnd["centrale"] = $end;
            }
        }

        return [$postiLiberi, $seqConsecMax, $seqConsecEnd, $listaPostiQuery];
    } else {
        unset($listaPostiStruct);
        header("Location: 500.php");
        die();
    }
}
?>
