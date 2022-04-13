<?php
$site_template=file_get_contents("../html/template.html");
$sessiondiscard_timer=200;

function CheckSession($login_required, $admin_required){
    GLOBAL $sessiondiscard_timer;
    $now = time();
    if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
        session_unset();
        session_destroy();
        session_start();
        header("location:area_utenti.php?action=login_page");
    }
    $_SESSION["discard_after"] = $now+$sessiondiscard_timer;

    if($admin_required){
        if(!isset($_SESSION["admin"])||!$_SESSION["admin"]){
            header("location:area_utenti.php?action=login_page");
            exit();
        }
    }
    if($login_required){
        if(!isset($_SESSION["a"])){
            header("location:area_utenti.php?action=login_page");
            exit();
        }
    }
}
$menuvoices=[
    "Home" => "home.php",
    "Contatti" => "contatti.php",
    "Programmazione" => "programmazione.php",
    "Info e Costi" => "info.php",
];
function GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody){
    GLOBAL $site_template;
    GLOBAL $menuvoices;
    $output= $site_template;
    $output = str_replace('<PAGETITLE/>', $title, $output);
    $output = str_replace('<KEYWORDS/>', $keywords, $output);
    $output = str_replace('<DESCRIPTION/>', $description, $output);
    $output = str_replace("<JAVASCRIPT-HEAD/>", $jshead, $output);
    $menu='<ul id="menu" class="closedMenu">';
    foreach($menuvoices as $name => $link){

		if($name!=$page){
            $menu=$menu."<li><a href=".$link.">".$name."</a></li>";
        }
        else{
            $menu=$menu."<li class=\"menu_name\">".$name."</li>";
        }
    }
    $menu=$menu."<li>";
    if (isset($_SESSION["a"])){
        $menu=$menu.'<a id="loginbutton" href="./area_utenti.php?action=getProfile">'.$_SESSION["a"].'</a>';
    }
    else
    {
	    $menu=$menu.'<a id="loginbutton" href="./area_utenti.php?action=login_page">Login</a>';
    }
    $menu=$menu."</li>";
    $menu=$menu."</ul>";
    $output = str_replace("<MENU/>",$menu , $output);
    $admin="";
    if(isset($_SESSION["admin"])&&$_SESSION["admin"])
    {
        $admin="<ul><li><a href='admin.php'>Amministrazione</a></li></ul>";
    }
    $output = str_replace("<ADMIN/>",$admin , $output);
    $output = str_replace("<BREADCRUMB/>",$breadcrumbs , $output);
    $output = str_replace("<CONTENT/>", $content, $output);
    $output = str_replace("<JAVASCRIPT-BODY/>", $jsbody, $output);
    return $output;
}
?>