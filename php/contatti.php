<?php
session_start(); 
    $document = file_get_contents('../html/template.html'); //load template
    $home_content = file_get_contents('../html/contatti_content.html'); //load content
    $document = str_replace('<CONTENT>', $home_content, $document); //fills template with content
	if(isset(	$_SESSION['a'] ))
	{
		
		$document=str_replace('<LOGIN>', $_SESSION['a'] , $document);
		
		
	}else{
		
		$document=str_replace('<LOGIN>', "Login" , $document);
		 //echo($_SESSION['a']);
	}
    echo($document);

?>