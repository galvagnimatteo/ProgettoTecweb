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
                    "INSERT INTO Utente(Username,Email,Nome,Cognome,Password) VALUES (?,?,?,?,?)";
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
                    "SELECT Username FROM Utente WHERE Email=? AND Password=?";
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
				"SELECT * FROM Utente WHERE  Email=?  OR Username=?";

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
                "SELECT Username, Nome,Cognome,Password,Email FROM Utente WHERE Username=? ";
            $preparedQuery = $db->getConnection()->prepare($query);
			$username = $_SESSION["a"];
            $preparedQuery->bind_param("s", $username);

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
                $row["Username"] .
                '"  required>
		</div>
		</div>


			<div class="row">
		<div class="col_25">
		<label for="name_profile"><span xml:lang="en">Name</span></label>
		</div>
		<div class="col_75">

		<input type="text" placeholder=" Name" name="name_profile" value="' .
                $row["Nome"] .
                '"  required   >
			</div>
		</div>



		<div class="row">
		<div class="col_25">
		<label for="surname_profile"><span xml:lang="en">Surname</span></label>
		</div>
		<div class="col_75">
		<input type="text" placeholder=" Surname" name="surname_profile"   value="' .
                $row["Cognome"] .
                '"   required>
		</div>
		</div>


		<div class="row">
		<div class="col_25">
		<label for="email_profile"><span xml:lang="en">Email</span></label>
		</div>
			<div class="col_75">
		<input type="email" placeholder=" Email" name="email_profile"  value="' .
                $row["Email"] .
                '"   >
		</div>
		</div>

		<div class="row">
		<div class="col_25">
		<label for="password_profile"><span xml:lang="en">Password</span></label>
		</div>
		<div class="col_75">
		<input type="password" placeholder=" Password" name="password_profile"   value="' .
                $row["Password"] .
                '"   >
		</div>
		</div>

		<div class="row">
		<div class="col_25">
		<label for="pass_profile_confirm"><span xml:lang="en">Password Confirmation</span></label>
		</div>
			<div class="col_75">
		<input type="password" placeholder=" Repeat Password" name="pass_profile_confirm"  value="' .
                $row["Password"] .
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
                  "DELETE FROM Utente WHERE Username=?";
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
                  "UPDATE Utente SET Nome=?, Cognome=?, Password=?,Email=? WHERE Username=?";
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
