<?php
require_once "SingletonDB.php";
require_once "utils/controls.php";


class Users
{
    

	
	function insert()
    {
	

        if (
            isset($_POST["name_register"]) &&
            isset($_POST["username_register"]) &&
            isset($_POST["password_register"]) &&
            isset($_POST["email_register"]) &&
			isset($_POST["surname_register"]) &&
            isset($_POST["pass_register_confirm"])
        ) {


            $username = $_POST["username_register"];
            $password = $_POST["password_register"];
            $name = $_POST["name_register"];
            $surname = $_POST["surname_register"];
            $email = $_POST["email_register"];

            $confirm_password = $_POST["pass_register_confirm"];

            $result = registerControls($username, $name, $surname, $email, $password, $confirm_password);

            if($result == "OK"){

                $db = SingletonDB::getInstance();

                $query =
                    "INSERT INTO utente(username,email,nome,cognome,password) VALUES (?,?,?,?,?)";
                $preparedQuery = $db->getConnection()->prepare($query);
                $preparedQuery->bind_param(
                    "sssss",
                    $username,
                    $email,
                    $name,
                    $surname,
                    $password
                );
				

                $_SESSION["a"] = $username;

                $preparedQuery->execute();
                $db->disconnect();
                $preparedQuery->close();

            }

            return $result;

        }
    }

    function search()
    {
		
        if (isset($_POST["email_login"]) && isset($_POST["password_login"])) {

            $email = $_POST["email_login"];
            $password = $_POST["password_login"];

            $result = loginControls($email, $password);

            if($result == "OK"){

                $db = SingletonDB::getInstance();
                $query =
                    "SELECT username FROM utente WHERE  email=? AND password=?";
                $preparedQuery = $db->getConnection()->prepare($query);
                $preparedQuery->bind_param("ss", $email, $password);

                $preparedQuery->execute();
                $resultCast = $preparedQuery->get_result();

                $db->disconnect();
                $preparedQuery->close();

                if ($resultCast->num_rows > 0) {
                    $row = $resultCast->fetch_assoc();
                    $_SESSION["a"] = $row["username"];

                    header("location:home.php");
                } else {
                    unset($_SESSION["a"]);
                    header("location:area_utenti.php?action=login_page");
                }

            }

            return $result;
        }

    }
	
	function searchRegistered(){
		
			
		if(
			isset($_POST["username_register"])&&
			isset($_POST["name_register"])&&            
			isset($_POST["password_register"])&&
            isset($_POST["email_register"])&&
            isset($_POST["surname_register"])
		){
			
			
			$db = SingletonDB::getInstance();
			$query =
				"SELECT * FROM utente WHERE  email=?  OR username=?";
				
		$preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param("ss", $email1,$username1);
			
			$username1 = $_POST["username_register"];	
			$email1 = $_POST["email_register"];
			
			$preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();
				
			
				 if ($resultCast->num_rows > 0){
					  $db->disconnect();
                $preparedQuery->close();
			return true;	 
				}	 
			
            
		
			
		return false;
		
		
	}
}
    function getProfile()
    {
        if (isset($_SESSION["a"])) {
            $db = SingletonDB::getInstance();

            $query =
                "SELECT username,nome,cognome,password,email FROM utente WHERE  username=? ";
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param("s", $username);
            $username = $_SESSION["a"];

            $preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();
            $db->disconnect();
            $preparedQuery->close();
            $row = $resultCast->fetch_assoc();
			
			 $home_content = file_get_contents("../html/items/updateProfile_content.html"); 
		     $home_content = str_replace("<USERNAME>", $row["username"] ,  $home_content);
		     $home_content = str_replace("<NOME>",  $row["nome"] ,  $home_content);
		     $home_content = str_replace("<COGNOME>", $row["cognome"] ,  $home_content);
		     $home_content = str_replace("<EMAIL>", $row["email"],  $home_content);
		     $home_content = str_replace("<PASSWORD>", $row["password"] ,  $home_content);
             return $home_content; 
			
			
            
        }
    }
	function deleteProfile()
	{
	$db = SingletonDB::getInstance();

            $query =
                  "DELETE FROM utente WHERE username=?";
		  if ($preparedQuery = $db->getConnection()->prepare($query)) {
			 $preparedQuery->bind_param("s",$username);	
			if(isset($_SESSION["a"]))
			{
			 $username = $_SESSION["a"];
			$preparedQuery->execute();
			$db->disconnect();
            $preparedQuery->close();	
			return true;
			 }else{
				 return false;
			 }
		 }
		 return false;
		
	}	

    function changeProfile()
    {
        if (
            isset($_POST["name_profile"]) &&
            isset($_POST["password_profile"]) &&
            isset($_POST["email_profile"]) &&
            isset($_POST["surname_profile"]) &&
            isset($_POST["password_profile"]) &&
            isset($_SESSION["a"])
        ) {
            $db = SingletonDB::getInstance();


            $query =
                  "UPDATE utente SET nome=?, cognome=?, password=?,email=? WHERE username=?";
            if ($preparedQuery = $db->getConnection()->prepare($query)) {
                $preparedQuery->bind_param(
                    "sssss",
                    $name,
                    $surname,
                    $password,
                    $email,
                    $username
                );

                $username = $_SESSION["a"];
                $password = $_POST["password_profile"];
                $name = $_POST["name_profile"];
                $surname = $_POST["surname_profile"];
                $email = $_POST["email_profile"];


                $preparedQuery->execute();

                $db->disconnect();
                $preparedQuery->close();
				return true;
            } else {
				return false;
            }
        }
    }
}

?>
