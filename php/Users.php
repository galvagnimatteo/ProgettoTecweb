<?php
require_once("SingletonDB.php");


class Users {
	
	
 function insert()
 {
			
	
if(isset($_POST['name_register']) &&isset($_POST['username_register']) &&isset($_POST['password_register'])
	&&isset($_POST['email_register']) &&isset($_POST['surname_register']))

	{
	$username=$_POST['username_register'];
	$password=$_POST['password_register'];
	$name=$_POST['name_register'];
	$surname=$_POST['surname_register'];
	$email=$_POST['email_register'];
	$_SESSION['a'] = $username;
	$sql = SingletonDB::getInstance();
	
	$query = "INSERT INTO utente ( username,email,nome, cognome,password) VALUES ('$username','$email','$name','$surname','$password')";	
	
	$sql->executeQuery($query); 
	$sql->disconnect(); 
	 
	 
	header("location:home.php");
	
	}

}

function search()
{
	
	
	
	if(isset( $_POST['email_login'])   &&  isset( $_POST['password_login'])   )
	{
		$email=$_POST['email_login'];
		$password=$_POST['password_login'];
		
		$sql = SingletonDB::getInstance();
		$query = "SELECT username FROM utente WHERE  email= '$email' AND password='$password' ";	
		$result=$sql->executeQuery($query); 
			
		
		
		
	if( $result->num_rows > 0)
	{
		
				$row=$result->fetch_assoc();
				$_SESSION['a'] = $row['username'];
				$sql->disconnect(); 
				header("location:home.php");
	}else
	{	
			unset($_SESSION['a']);
			header("location:area_utenti.php?action=login_page");
	}
	 
	}
}

	
	
	
	
	
	
	
}




?>