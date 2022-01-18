<?php
session_start();   
    $document = file_get_contents('../html/template.html'); 
   
    

	
	
	 	if(isset(	$_SESSION['a'] ))
	   {
		   
		   $document=str_replace('<LOGIN>', $_SESSION['a'] , $document);
		   
		   $home_content = file_get_contents('../html/home_content.html');
		   
		   
	   }else{
		   
		$document=str_replace('<LOGIN>', "Login" , $document);   
		   
	   }
	   
	   
	   
 if(isset($_GET['action']))
  {
	  $action = $_GET['action'];
	  
	  if($action == 'login_page')
	  {
			
	  $home_content = file_get_contents('../html/items/area_utenti_login.html'); 
	
	  }
	  
	   if($action == 'register_page')
	  {
		  
	$home_content = file_get_contents('../html/area_utenti_register_content.html');

	 
	  }
	  
	  
	 
	if($action == 'register')
	{
	  
	include_once 'Users.php';
	$Users=new Users();
	$Users->insert();

	}
	
	if($action == 'search'){
			
		include_once 'Users.php';
		$Users=new Users();
	$Users->search();
	 
	}
	
  
  
  
  }else
  {
	$home_content = file_get_contents('../html/area_utenti_register_content.html');  
$document=str_replace('<LOGIN>', "Login" , $document);	
  }
	  
	  
	  
	
	$document = str_replace('<CONTENT>', $home_content, $document);
	echo($document);
	 
?>