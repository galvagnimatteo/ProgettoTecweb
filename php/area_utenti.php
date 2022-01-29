<?php

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {

	unset($_SESSION["a"]);

    session_unset();
    session_destroy();
    session_start();
	header("location:area_utenti.php?action=login_page");
}

$_SESSION["discard_after"] = $now + 30;

$document = file_get_contents("../html/template.html"); //load template
$home_content = file_get_contents("../html/area_utenti_register_content.html"); //load content


$document = str_replace(
    "<BREADCRUMB>",
    '<a href="home.php">Home</a> / <a href="area_utenti.php?action=login_page">Area Utenti</a>',
    $document
);
$document = str_replace("<JAVASCRIPT-HEAD>", '<script type="text/javascript" src="../js/controls.js"> </script>', $document);

$document = str_replace("<JAVASCRIPT-BODY>", "", $document);

if (isset($_SESSION["a"])) {
    $document = str_replace("<LOGIN>", $_SESSION["a"], $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=getProfile",
        $document
    );

    $home_content = file_get_contents("../html/home_content.html");
} else {
    $document = str_replace("<LOGIN>", "Login", $document);
    $document = str_replace(
        "<LINK>",
        "./area_utenti.php?action=login_page",
        $document
    );
}

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
			$home_content = str_replace("<ERRORMESSAGE>", "Email/Username già registrati", $home_content);		
			}else{
			if($isUser )
			{	
			$home_content = str_replace("<ERRORMESSAGE>", "Username già registrato", $home_content);
			}
			if($isEmail )
			{	
			$home_content = str_replace("<ERRORMESSAGE>", "Email già registrato", $home_content);
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

    if ($action == "getProfile") {
        include_once "Users.php";
        $Users = new Users();
        $home_content = $Users->getProfile();
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
			
			$Users->changeProfile();
		header("location:home.php");
			
			
		}
		
    }

	if($action == "deleteProfile")
	{
	 include_once "Users.php";
     $Users = new Users();
     if($Users->deleteProfile())
	 {
		session_destroy();
		unset($_SESSION["a"]);
		    header("location:area_utenti.php?action=login_page");
		 } 
	}	

} else {
    $home_content = file_get_contents(
        "../html/area_utenti_register_content.html"
    );
    $document = str_replace("<LOGIN>", "Login", $document);
}


$home_content = str_replace("<ERRORMESSAGE>", " ", $home_content); //se è ancora presente <errormessage> viene tolto, non funziona se non presente (già sostituito con errore)

$document = str_replace("<CONTENT>", $home_content, $document);
echo $document;

?>
