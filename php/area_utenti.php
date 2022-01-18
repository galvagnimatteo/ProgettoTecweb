<?php
    session_start();
    $document = file_get_contents('../html/template.html'); //load template
    $home_content = file_get_contents('../html/area_utenti_register_content.html'); //load content


    $document = str_replace('<PAGETITLE>', 'Area Utenti - PNG Cinema', $document);
    $document = str_replace('<KEYWORDS>', 'area utenti, utente, login, registrazione, area personale', $document);
    $document = str_replace('<DESCRIPTION>', 'Area utenti: è possibile registrarsi o effettuare l\'accesso al sito.', $document);
    $document = str_replace('<BREADCRUMB>', '<a href="home.php">Home</a> / <a href="area_utenti.php">Area Utenti</a>', $document);

    $document = str_replace('<CONTENT>', $home_content, $document); //fills template with content

    $document = str_replace('<JAVASCRIPT-HEAD>', '', $document);
    $document = str_replace('<JAVASCRIPT-BODY>', '', $document);

  if(isset($_GET['action']))
  {

	  $action = $_GET['action'];
	 	  if($action == 'insert')
	  {

	  		include_once 'Users.php';
	$Users=new Users();
	$Users->insert();
  }
  }else{

    echo($document);

  }
?>