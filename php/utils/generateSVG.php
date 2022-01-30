<?php
	
	function generateSVG($sala, $idproiez, $orario) {
		$db = SingletonDB::getInstance();
		$db->connect();
		$preparedQuery = $db
			->getConnection()
			->prepare("SELECT Posto.Numero, Posto.Fila FROM Posto INNER JOIN Sala ON Sala.Numero = Posto.NumeroSala WHERE Sala.Numero = ? ORDER BY Posto.Fila, Posto.Numero");
		$preparedQuery->bind_param("i", $sala);
		$preparedQuery->execute();
		$result1 = $preparedQuery->get_result();
		
		
		$preparedQuery2 = $db
			->getConnection()
			->prepare("SELECT NumeroPosto as Numero, FilaPosto as Fila FROM Prenotazione INNER JOIN Partecipa ON Prenotazione.ID=Partecipa.IDPrenotazione WHERE Prenotazione.IDProiezione=? AND Prenotazione.OraProiezione=?");
		$preparedQuery2->bind_param("is", $idproiez, $orario);
		$preparedQuery2->execute();
		$result2 = $preparedQuery2->get_result();
				
		$db->disconnect();
		
		$statoPosti = array();

		
		$SVG = '';
		if (!empty($result1) && $result1->num_rows) {
			
			while($row = $result1->fetch_assoc()) {
				$statoPosti[$row["Fila"] . $row["Numero"]] = 'libero';  //inizializzo tutti liberi
			}
			
			if(!empty($result2) && $result2->num_rows > 0){ //se ci sono posti occupati
				while($row = $result2->fetch_assoc()) {
					$statoPosti[$row["Fila"] . $row["Numero"]] = 'occupato';  //posti occupati
				}
			}
			
			
			$SVG .= '<svg version="1.1" width="95%" xmlns="http://www.w3.org/2000/svg" title="Mappa della sala per la scelta dei posti">' .
					'<rect width="100%" height="100%" fill="red" />' .
					'<g id="scene">"' .
					'<g class="center">';
			
			$r = 20;
			$cx = 30;
			$cy = 30;
			$tx = 23;
			$ty = 38;
			$lastRow = "a";
			$SVG .= '<text class="letteraFila" x="' . $tx - 40 . '" y="' . $ty . '">A</text>';

			foreach($statoPosti as $codice => $stato) {
				$fila= substr($codice, 0, 1);
				$count = intval(substr($codice, 1));
				
				if ($lastRow != strtolower($fila)) {
					$SVG .= '<text class="letteraFila" x="' . $tx-8 . '" y="' . $ty . '">' . strtoupper($lastRow) . '</text>';
					$cy += 60;
					$ty += 60;
					$cx = 30;
					$tx = 23;
					$SVG .= '<text x="' . $tx - 40 . '" y="' . $ty . '">' . strtoupper($fila) . '</text>';
				}
				
				$lastRow = strtolower($fila);
				
				
				$SVG .= '<g data-codice="' . strtolower($fila) . $count . '" class="seat '. $stato .'">' .
						'<circle cx="' . $cx . '" cy="'. $cy .'" r="'. $r . '"/>' .
						'<text fill="white" x="' . $tx . '" y="' . $ty . '">' . $count . '</text>' .
						'</g>';
				
				if ($count != 9) { 
					$cx += 50;
					$tx += 50;
				} else {
					$cx += 50;
					$tx += 44;
				}
			}
			
			$SVG .= '<text class="letteraFila" x="' . $tx-8 . '" y="' . $ty . '">' . strtoupper($lastRow) . '</text>';
			$SVG .= '</g></g></svg>';
				
		} else {
			
			header("Location: 500.php");
			die();
		}
		
		
		
		return $SVG;
	}

?>