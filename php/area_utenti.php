<?php

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
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
    '<a href="home.php">Home</a> / <a href="area_utenti.php">Area Utenti</a>',
    $document
);
$document = str_replace("<JAVASCRIPT-HEAD>", "", $document);
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
	//controlla se l'utente non è già registrato	
		if(!($Users->search()))	
		{	
		 $Users->insert();
		}else{
		$_SESSION["register_error"]=true;	
		header("location:area_utenti.php?action=register_page");	
		}	
    }

    if ($action == "search") {
        include_once "Users.php";
        $Users = new Users();
		 if($Users->search())
		{
		header("location:home.php");
		}else{
		header("location:area_utenti.php?action=login_page");
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
        $Users->changeProfile();
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

$document = str_replace("<CONTENT>", $home_content, $document);
echo $document;

?>
