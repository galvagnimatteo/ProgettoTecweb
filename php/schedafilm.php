<?php
	include 'SingletonDB.php';
	include 'utils/createCastStr.php';

	if (isset($_GET["idfilm"]) && is_numeric($_GET["idfilm"])) {

		$db = SingletonDB::getInstance();

		$preparedQuery = $db->getConnection()->prepare('SELECT * FROM Film WHERE Film.ID=?');
		$preparedQuery->bind_param("i", $_GET['idfilm']);
		$preparedQuery->execute();
		$result1 = $preparedQuery->get_result();

		$preparedQuery2 = $db->getConnection()->prepare('SELECT * FROM CastFilm JOIN Afferisce ON CastFilm.ID = Afferisce.IDCast WHERE Afferisce.IDFilm =?');
		$preparedQuery2->bind_param("i", $_GET['idfilm']);
		$preparedQuery2->execute();
		$result2 = $preparedQuery2->get_result();

		$preparedQuery3 = $db->getConnection()->prepare('SELECT * FROM Proiezione INNER JOIN Film ON Proiezione.IDFilm = Film.ID WHERE Film.ID=?');
		$preparedQuery3->bind_param("i", $_GET['idfilm']);
		$preparedQuery3->execute();
		$result3 = $preparedQuery3->get_result();

		$db->disconnect();

		if (!empty($result1) && $result1->num_rows > 0 && !empty($result2) && $result2->num_rows > 0) { //si assume che se c'è un film ha un cast e un direttore, per questo il controllo unico
			$dataFilm = $result1->fetch_assoc();
			$cast = createCastStr($result2);

			$document = file_get_contents('../html/template.html');
			$schedafilm_content = file_get_contents('../html/schedafilm_content.html');

			$document = str_replace('<PAGETITLE>', $dataFilm['Titolo'] . ' - PNG Cinema', $document);
			$document = str_replace('<KEYWORDS>',
			$dataFilm['Titolo'], $document);
			$document = str_replace('<DESCRIPTION>',
			'Scheda informativa del film: ' . $dataFilm['Titolo'], $document);
			$document = str_replace('<BREADCRUMB>', '<a href="home.php">Home</a> / <a href="programmazione.php">Programmazione</a> / <a href="schedafilm.php?idfilm=' . $dataFilm['ID'] . '">Scheda Film: ' . $dataFilm['Titolo'] . '</a>', $document);

			$document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
			$document = str_replace('<JAVASCRIPT-BODY>', '', $document);

			$schedafilm_content = str_replace('<FILM-TITLE>', $dataFilm['Titolo'], $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-IMG>', '<img src=\''. $dataFilm['SrcImg'] .' \' alt=\'' . $dataFilm['AltImg'] . '\'/>', $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-GENRE>', $dataFilm['Genere'] , $schedafilm_content);
			$schedafilm_content = str_replace('<RUNNING-TIME>', $dataFilm['Durata'] . ' min' , $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-DIRECTOR>', $cast['R'], $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-CAST>',  $cast['A'], $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-DESC>',  $dataFilm["Descrizione"], $schedafilm_content);

			if(!empty($result3) && $result3->num_rows){

				$hour_field_template = file_get_contents('../html/items/hour-field.html');
				$hour_fields = "";

				while($row = $result3->fetch_assoc()){

					$hour_field = $hour_field_template;
					$hour_field = str_replace('<DATA>',  $row["Data"], $hour_field);

					$hour_field = str_replace('<HOUR>',  $row["Orario"], $hour_field); //TODO ci sono più orari per data, va modificato il db non essendo in forma normale
					$hour_fields .= $hour_field;


				}

				$schedafilm_content = str_replace('<HOUR-FIELDS>',  $hour_fields, $schedafilm_content);

			} //else nessun problema, il film non ha programmazioni in corso



			$document = str_replace('<CONTENT>', $schedafilm_content, $document);


			echo $document;

		}else{
			header("Location: 404.php");
			die();
		}

	}else{

		header('Location: 404.php');
		die();

	}






?>