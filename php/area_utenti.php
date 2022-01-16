<?php
session_start();
    $document = file_get_contents('../html/template.html'); //load template
    $home_content = file_get_contents('../html/area_utenti_register_content.html'); //load content
    $document = str_replace('<BREADCRUMB>', '<a href="home.php">Home</a> / <a href="#">Area Utenti</a>', $document);
    $document = str_replace('<CONTENT>', $home_content, $document); //fills template with content




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