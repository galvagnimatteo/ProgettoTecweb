<?php
session_start();   
    include 'SingletonDB.php';
    include 'mostra_errori.php';

    $document = file_get_contents('../html/template.html');
    $home_content = file_get_contents('../html/home_content.html');

    $db = SingletonDB::getInstance();
    $result = $db->executeQuery("SELECT * FROM Film"); //TODO fare la query completa
    $db->disconnect();

    $cards = "";
		if(isset(	$_SESSION['a'] ))
	{
		
		$document=str_replace('<LOGIN>', $_SESSION['a'] , $document);
		
		
	}else{
		
		$document=str_replace('<LOGIN>', "Login" , $document);
		 
	}

    if(!empty($result) && $result->num_rows > 0){

        $card_home_template = file_get_contents('../html/items/card-home.html');

        while($row = $result->fetch_assoc()) {

            $card_home_item = $card_home_template;

            $card_home_item = str_replace('<FILMTITLE>', $row["Titolo"], $card_home_item);
            $card_home_item = str_replace('<FILMDIRECTOR>', "test", $card_home_item);
            $card_home_item = str_replace('<FILMCAST>', "test", $card_home_item);

            $cards = $cards . $card_home_item;

        }

    }else{

        $cards = "Nessun film trovato."; //TODO display errore

    }

    $home_content = str_replace('<CARDS-HOME>', $cards, $home_content);

    $document = str_replace('<CONTENT>', $home_content, $document);
	


    echo($document);

?>