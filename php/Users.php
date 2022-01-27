<?php
require_once "SingletonDB.php";

class Users
{
    

    function search()
    {
	$db = SingletonDB::getInstance();
	
        if ((isset($_POST["email_login"]) && isset($_POST["password_login"])))
		 {
			$query =
				"SELECT username FROM utente WHERE  email=? AND password=?";	
		
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param("ss", $email, $password);

            $email = $_POST["email_login"];
            $password = $_POST["password_login"];

            $preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();

            $db->disconnect();
            $preparedQuery->close();
	
           if ($resultCast->num_rows > 0) {
                $row = $resultCast->fetch_assoc();
                $_SESSION["a"] = $row["username"];
				return true;
            } else {
                unset($_SESSION["a"]);
				return false;            
            }
        }else{
			
		if(
			isset($_POST["username_register"])&&
			isset($_POST["name_register"])&&            
			isset($_POST["password_register"])&&
            isset($_POST["email_register"])&&
            isset($_POST["surname_register"])
		){
			
			$query =
				"SELECT * FROM utente WHERE  email=? AND username=?";
				
			$preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param("ss", $email, $password);
				
			$email = $_POST["email_register"];
            $password = $_POST["username_register"];	
			
			$preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();
			
			 if ($resultCast->num_rows > 0){
			return true;	 
		}else{
			return false;		 
				 }		 
			$db->disconnect();
            $preparedQuery->close();	
			}
		}
	}
	
	function insert()
    {
	
        if (
            isset($_POST["name_register"]) &&
            isset($_POST["username_register"]) &&
            isset($_POST["password_register"]) &&
            isset($_POST["email_register"]) &&
            isset($_POST["surname_register"])
        ) {
			
		
            $db = SingletonDB::getInstance();

            $query =
                "INSERT INTO utente ( username,email,nome, cognome,password) VALUES (?,?,?,?,?)";
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param(
                "sssss",
                $username,
                $email,
                $name,
                $surname,
                $password
            );

            $username = $_POST["username_register"];
            $password = $_POST["password_register"];
            $name = $_POST["name_register"];
            $surname = $_POST["surname_register"];
            $email = $_POST["email_register"];

            $_SESSION["a"] = $username;

            $preparedQuery->execute();
            $db->disconnect();
            $preparedQuery->close();

            header("location:home.php");
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

            return $home_content =
                '

		 <div class="div_user">
	Modify your profile
	 </div>
	   <div class="div_register">



		<form action="../php/area_utenti.php?action=changeProfile" method="post" >

	<div class="row">
		<div class="col_25">
		<label for="username_profile"><span xml:lang="en">Username</span></label>
		</div>
		<div class="col_75">
		<input type="text" placeholder=" Username" name="username_profile"  value="' .
                $row["username"] .
                '"  required>
		</div>
		</div>


			<div class="row">
		<div class="col_25">
		<label for="name_profile"><span xml:lang="en">Name</span></label>
		</div>
		<div class="col_75">

		<input type="text" placeholder=" Name" name="name_profile" value="' .
                $row["nome"] .
                '"  required   >
			</div>
		</div>



		<div class="row">
		<div class="col_25">
		<label for="surname_profile"><span xml:lang="en">Surname</span></label>
		</div>
		<div class="col_75">
		<input type="text" placeholder=" Surname" name="surname_profile"   value="' .
                $row["cognome"] .
                '"   required>
		</div>
		</div>


		<div class="row">
		<div class="col_25">
		<label for="email_profile"><span xml:lang="en">Email</span></label>
		</div>
			<div class="col_75">
		<input type="email" placeholder=" Email" name="email_profile"  value="' .
                $row["email"] .
                '"   >
		</div>
		</div>

		<div class="row">
		<div class="col_25">
		<label for="password_profile"><span xml:lang="en">Password</span></label>
		</div>
		<div class="col_75">
		<input type="password" placeholder=" Password" name="password_profile"   value="' .
                $row["password"] .
                '"   >
		</div>
		</div>

		<div class="row">
		<div class="col_25">
		<label for="pass_profile_confirm"><span xml:lang="en">Password Confirmation</span></label>
		</div>
			<div class="col_75">
		<input type="password" placeholder=" Repeat Password" name="pass_profile_confirm"  value="' .
                $row["password"] .
                '"   required>
	</div>
		</div>





	<div class="row">
	<input type="submit" value="Confirm your profile" >

	 </div>



	</form>
	</div>

	<div class="div_user">
	
	
	<form action="../php/area_utenti.php?action=deleteProfile" method="post" >

	<button type="submit" class="link_button"> Delete your account </button>
	</form>
	</div>



	   ';
        }
    }
	function deleteProfile()
	{
	$db = SingletonDB::getInstance();

            $query =
                  "DELETE FROM utente WHERE username=?";
		 if ($preparedQuery = $db->getConnection()->prepare($query)) {
			 $preparedQuery->bind_param("s",$username);		
			 $username = $_SESSION["a"];
			$preparedQuery->execute();
			$db->disconnect();
            $preparedQuery->close();	
			return true;
			 }else{
				 return false;
			 }
		
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
            } else {
            }
        }
    }
}

?>
