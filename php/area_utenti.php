<?php

session_start();
include "utils/pageGenerator.php";
//CheckSession($login_required, $admin_required);
CheckSession(false,false); //refresh della sessione se scaduta
$home_content = file_get_contents("../html/area_utenti_register_content.html"); //load content

if (isset($_GET["action"])) {
    $action = $_GET["action"];

    if ($action == "login_page") {
        $home_content = file_get_contents(
            "../html/items/area_utenti_login.html"
        );
    }

    if ($action == "register_page") {
        $home_content = file_get_contents(
            "../html/area_utenti_register_content.html"
        );
    }

    if ($action == "register_user") {
        include_once "Users.php";


      $Users = new Users();

		list($isDoubled,$isUser,$isEmail)=$Users->searchRegistered($_POST["email_register"],$_POST["username_register"],$value=0);

		if($isDoubled)
		{
			if($isUser && $isEmail) {
			$home_content = str_replace("<ERRORMESSAGE>", "Email e username già registrati", $home_content);
			}else{
			if($isUser )
			{
			$home_content = str_replace("<ERRORMESSAGE>", "Username già registrato", $home_content);
			}
			if($isEmail )
			{
			$home_content = str_replace("<ERRORMESSAGE>", "Email già registrata", $home_content);
			}

			}
		}else
		{

		 $result = $Users->insert();


        if($result == "OK"){

            header("location:home.php");

        }else{

            //display error in result
            $home_content = str_replace("<ERRORMESSAGE>", $result, $home_content);

        }



		}

    }

    if ($action == "search") {

        include_once "Users.php";
        $Users = new Users();
        $result = $Users->search();

        $home_content = file_get_contents(
            "../html/items/area_utenti_login.html"
        );

        if(!($result == "OK")){

            //display error in result
            $home_content = str_replace("<ERRORMESSAGE>", $result, $home_content);

        }

    }

	if ($action =="getHistoryProfile")
	{
		include_once "Users.php";
        $Users = new Users();
        $home_content = $Users->getHistory();

	}
    if ($action == "getProfile") {
        include_once "Users.php";
        $Users = new Users();
        $home_content = $Users->getProfile();
    }
	 if ($action == "getProfilePassword") {
        include_once "Users.php";
        $Users = new Users();
		$home_content = file_get_contents("../html/items/updatePassword_content.html");
	}

	 if ($action == "getDeleteProfile") {
        include_once "Users.php";
        $Users = new Users();
         $home_content = file_get_contents(
            "../html/items/deleteProfile_content.html"
        );
    }
     if ($action == "changeProfile") {
      include_once "Users.php";
        $Users = new Users();


		list($isDoubled,$isUser,$isEmail)=$Users->searchRegistered($_POST["email_profile"],$_POST["username_profile"],$value=1);

		if($isDoubled)
		{

			if($isUser && $isEmail) {

			header("location:area_utenti.php?action=getProfile&error=3");
			}else{
			if($isUser )
			{

			header("location:area_utenti.php?action=getProfile&error=2");
			}
			if($isEmail )
			{

			header("location:area_utenti.php?action=getProfile&error=1");
			}

			}
		}else{

			$result = $Users->changeProfile();
			if($result == "OK"){

				header("location:home.php");

			}else{

				$_SESSION["insertError"] = $result;

				header("location:area_utenti.php?action=getProfile");

			}



		}

    }

	if($action == "deleteProfile")
	{
	 include_once "Users.php";
     $Users = new Users();
	 list($valid,$error)=$Users->checkPassword(nil,$_POST["password_delete"],$_POST["password_delete_confirm"]);
	 if(!$valid)
	 {
		  header("location:area_utenti.php?action=getDeleteProfile&errorPass=".$error);
	 }else{
     if($Users->deleteProfile())
	 {
			session_destroy();
		    header("location:area_utenti.php?action=login_page&errorPass=".$error);
	 }
	 }

	}

	if($action == "changePassword")
	{
	include_once "Users.php";
    $Users = new Users();
	list($valid,$error)=$Users->checkPassword($_POST["password_old"],$_POST["password_profile"],$_POST["password_profile_confirm"]);
	if(!$valid)
	{

	header("location:area_utenti.php?action=getProfilePassword&errorPass=".$error);
	}else{
	$Users->changePassword();
	header("location:area_utenti.php?action=getProfilePassword&errorPass=".$error);
	}
	}

	if($action == "viewReservation" && isset($_GET["codice"]))
	{
	include_once "Users.php";
    $Users = new Users();
	$home_content=$Users->viewReservation($_GET["codice"]);
	}


	if($action == "logout")
	{
	unset($_SESSION["a"]);
	unset($_SESSION["b"]);
	unset($_SESSION["admin"]);
	session_unset();
    session_destroy();
    session_start();
	header("location:area_utenti.php?action=login_page");
	}

} else {
    $home_content = file_get_contents(
        "../html/area_utenti_register_content.html"
    );
}
if(isset($_GET["errorLogin"]))
{
	$home_content = str_replace("<ERRORMESSAGE>", "Credenziali errate", $home_content);
	unset($_GET["errorLogin"]);
}

if(isset($_GET["errorPass"]))
{
	if($_GET["errorPass"]==0)
		$home_content = str_replace("<ERRORMESSAGE>", "Password cambiata", $home_content);


	if($_GET["errorPass"]==1)
		$home_content = str_replace("<ERRORMESSAGE>", "Password Errata", $home_content);


	if($_GET["errorPass"]==3)
		$home_content = str_replace("<ERRORMESSAGE>", "Le due password non coincidono", $home_content);


	if($_GET["errorPass"]==2)
		$home_content = str_replace("<ERRORMESSAGE>", "La password deve essere di almeno 8 caratteri e non può contenere spazi", $home_content);


	if($_GET["errorPass"]==4)
		$home_content = str_replace("<ERRORMESSAGE>", "Account eliminato!", $home_content);

	unset($_GET["errorPass"]);
}

$home_content = str_replace("<ERRORMESSAGE>", " ", $home_content); //se è ancora presente <errormessage> viene tolto, non funziona se non presente (già sostituito con errore)


$description = 'Pagina di login';
$keywords = 'Login';
$breadcrumbs='<a href="home.php">Home</a> /  Area Utenti';
$jshead ='<script type="text/javascript" src="../js/controls.js"> </script>';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage("login",$home_content,$breadcrumbs,'Login - PNG Cinema',$description,$keywords,$jshead,"");

?>
