<?php
function createCastStr($result) {
		$actors = ''; 
		$directors = '';
		if(!empty($result) && $result->num_rows > 0){
			while($row = $result->fetch_assoc()) {
				if ($row['Ruolo']=='A') 
					$actors .= $row['Nome'] . ' ' . $row['Cognome'] . ', '; 
				else 
					$directors .= $row['Nome'] . ' ' . $row['Cognome'] . ', '; 
			}
			//tolgo l'ultima virgola e spazio
			$actors = substr($actors, 0, -2);
			$directors = substr($directors, 0, -2);
		}
		return array('A' => $actors,
					 'R' => $directors);
	}
?>