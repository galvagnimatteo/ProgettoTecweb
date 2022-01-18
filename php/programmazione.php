<?php
session_start();
    include 'SingletonDB.php';
    include 'mostra_errori.php';

    $document = file_get_contents('../html/template.html');
    $programmazione_content = file_get_contents('../html/programmazione_content.html');

    $db = SingletonDB::getInstance();
    $result = $db->executeQuery("SELECT * FROM Film"); //TODO fare la query completa
    $db->disconnect();

    $cards = "";

    if(!empty($result) && $result->num_rows > 0){

        $card_prog_template = file_get_contents('../html/items/card-prog.html');

        while($row = $result->fetch_assoc()) {

            $card_prog_item = $card_prog_template;

            $card_prog_item = str_replace('<FILMTITLE>', $row["Titolo"], $card_prog_item);
            $card_prog_item = str_replace('<FILMDIRECTOR>', "test", $card_prog_item);
            $card_prog_item = str_replace('<FILMCAST>', "test", $card_prog_item);

            $cards = $cards . $card_prog_item;

        }

    }else{

        $cards = "Nessun film trovato."; //TODO display errore

    }

    $programmazione_content = str_replace('<CARDS-PROG>', $cards, $programmazione_content);

    $document = str_replace('<CONTENT>', $programmazione_content, $document);
	
	
		if(isset(	$_SESSION['a'] ))
	{
		
		$document=str_replace('<LOGIN>', $_SESSION['a'] , $document);
		
		
	}else{
		
		$document=str_replace('<LOGIN>', "Login" , $document);
		 
	}
	
    echo($document);

?>