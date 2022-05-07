<?php

function mappaPosti($numSala, $idproiez, $orario )
{
    //mappa posti
    $db = SingletonDB::getInstance();
    $db->connect();
    $preparedQuery = $db //posti totali
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
            "SELECT NumeroPosto as Numero, FilaPosto as Fila FROM Prenotazione INNER JOIN Occupa ON Prenotazione.ID=Occupa.IDPrenotazione INNER JOIN Proiezione ON Prenotazione.IDProiezione=Proiezione.ID WHERE Prenotazione.IDProiezione=? AND Proiezione.Orario=?"
        );
    $preparedQuery2->bind_param("is", $idproiez, $orario);
    $preparedQuery2->execute();
    $result2 = $preparedQuery2->get_result();

    $db->disconnect();

    $postiLiberi = $result1->num_rows - $result2->num_rows;

    if (!empty($result1) && $result1->num_rows) {
        $listaPostiQuery = $result1->fetch_all(MYSQLI_ASSOC);

        $listaPostiStruct = []; //array associativo nella forma [xy]=stato con x = fila posto y = numero posto, stato = 1 posto occupato, stato = 0 posto libero 
        foreach ($listaPostiQuery as $row) {
            $fila = strtolower($row["Fila"]);
            $listaPostiStruct[$fila . $row["Numero"]] = 0; //inizializzo tutti liberi

        }

        if (!empty($result2) && $result2->num_rows > 0) {
            //se ci sono posti occupati
           
			while ($row = $result2->fetch_assoc()) {
				$listaPostiStruct[
					strtolower($row["Fila"]) . $row["Numero"]
				] = 1; //posti occupati
			}
			
        } 
		
        return $listaPostiStruct;
    } else {
        unset($listaPostiStruct);
        
		header("Location: 500.php");
		die();
    }
}
?>
