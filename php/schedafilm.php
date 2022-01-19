<?php
	include 'SingletonDB.php';
	include 'utils/createCastStr.php';
	
	
	if (isset($_GET["idfilm"]) && is_numeric($_GET["idfilm"])) {
		
		$db = SingletonDB::getInstance();
		$result1 = $db->getConnection()->query('SELECT * FROM Film WHERE Film.ID=\''. $_GET['idfilm'] . '\'');
		$result2 = $db->getConnection()->query('SELECT * FROM CastFilm JOIN Afferisce ON CastFilm.ID = Afferisce.IDCast WHERE Afferisce.IDFilm = \''. $_GET["idfilm"] .'\'');
		$db->disconnect();
		
		if (!empty($result1) && $result1->num_rows > 0) {
			$dataFilm = $result1->fetch_assoc();
			$cast = createCastStr($result2);
        
			$document = file_get_contents('../html/template.html');
			$schedafilm_content = file_get_contents('../html/schedafilm_content.html');
			
			$document = str_replace('<PAGETITLE>', $dataFilm['Titolo'] . ' - PNG Cinema', $document);
			$document = str_replace('<KEYWORDS>', 
			$dataFilm['Titolo'], $document);
			$document = str_replace('<DESCRIPTION>', 
			'Scheda informativa del film: ' . $dataFilm['Titolo'], $document);
			$document = str_replace('<BREADCRUMB>', 
			'<a href="home.php">Home</a> / <a href="programmazione.php">Programmazione</a> / Scheda Film: ' . $dataFilm['Titolo'], $document);

			
			$schedafilm_content = str_replace('<FILM-TITLE>', $dataFilm['Titolo'], $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-IMG>', 
											 '<img src=\''. $dataFilm['SrcImg'] .' \' alt=\'' . $dataFilm['AltImg'] . '\'/>', $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-GENRE>', $dataFilm['Genere'] , $schedafilm_content);
			$schedafilm_content = str_replace('<RUNNING-TIME>', $dataFilm['Durata'] . ' min' , $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-DIRECTOR>', $cast['R'], $schedafilm_content);
			$schedafilm_content = str_replace('<FILM-CAST>',  $cast['A'], $schedafilm_content);

			
			
			$document = str_replace('<CONTENT>', $schedafilm_content, $document);
			$document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
			$document = str_replace('<JAVASCRIPT-BODY>', '', $document);
			
			echo $document;
			
		}
	}
	
		

	
	

?>