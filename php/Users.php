<?php
require_once("SingletonDB.php");


class Users {
	
	
 function insert(){
			
	
	
	if(isset($_POST['name_register']) &&isset($_POST['username_register']) &&isset($_POST['password_register'])
&&isset($_POST['email_register']))
{
	$username=$_POST['username_register'];
	$password=$_POST['password_register'];
	$name=$_POST['name_register'];
	$surname=$_POST['surname_register'];
	$email=$_POST['email_register'];
	$_SESSION['username_register'] = $username;
	$sql = SingletonDB::getInstance();
	
	$query = "INSERT INTO utente ( username,email,nome, cognome,password) VALUES ('$username','$email','$name','$surname','$password')";	
	
	$sql->executeQuery($query); 
	$sql->disconnect(); 
	 
	 
	header("location:home.php?username=$username");
	
	}

}

	
}




?>