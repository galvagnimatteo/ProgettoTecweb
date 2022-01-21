<?php
	session_start();

    include 'SingletonDB.php';
    include 'mostra_errori.php';

    $document = file_get_contents('../html/template.html');
    $programmazione_content = file_get_contents('../html/programmazione_content.html');

    $db = SingletonDB::getInstance();
    $result = $db->getConnection()->query("SELECT * FROM Film");
    $db->disconnect();

    $cards = "";

    if(!empty($result) && $result->num_rows > 0){

        $card_prog_template = file_get_contents('../html/items/card-prog.html');

        while($row = $result->fetch_assoc()) {

            $card_prog_item = $card_prog_template;

            $card_prog_item = str_replace('<FILMTITLE>', $row["Titolo"], $card_prog_item);
            $card_prog_item = str_replace('<FILMDIRECTOR>', "test", $card_prog_item);
            $card_prog_item = str_replace('<FILMCAST>', "test", $card_prog_item);
			$card_prog_item = str_replace('<FILM-PAGE>', "schedafilm.php?idfilm=" . $row['ID'], $card_prog_item);

            $cards = $cards . $card_prog_item;

        }

    }else{

        $cards = "Nessun film trovato.";

    }

    $programmazione_content = str_replace('<CARDS-PROG>', $cards, $programmazione_content);

    $document = str_replace('<PAGETITLE>', 'Programmazione - PNG Cinema', $document);
    $document = str_replace('<KEYWORDS>', 'programmazione, ultime uscite, ultimi film, film programmati, film in programma', $document);
    $document = str_replace('<DESCRIPTION>', 'Pagina sulla programmazione: è possibile consultare i film e le opere in programma nelle prossime settimane.', $document);
    $document = str_replace('<BREADCRUMB>', '<a href="home.php">Home</a> / <a href="programmazione.php">Programmazione</a>', $document);

    $document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
    $document = str_replace('<JAVASCRIPT-BODY>', '', $document);

    $document = str_replace('<CONTENT>', $programmazione_content, $document);
		if(isset(	$_SESSION['a'] ))
	{

		$document=str_replace('<LOGIN>', $_SESSION['a'] , $document);
		$document=str_replace('<LINK>', './area_utenti.php?action=getProfile' , $document);

	}else{

		$document=str_replace('<LOGIN>', "Login" , $document);
		$document=str_replace('<LINK>', './area_utenti.php?action=login_page' , $document);

	}

    echo($document);

?>