<?php
$site_template = file_get_contents("../html/template.html");
$sessiondiscard_timer = 1000;

function CheckSession($login_required, $admin_required)
{
    global $sessiondiscard_timer;
    $now = time();
    if (
        isset($_SESSION["discard_after"]) &&
        $now > $_SESSION["discard_after"]
    ) {
        session_unset();
        session_destroy();
        session_start();
        //header("location:area_utenti.php?action=login_page");
    }
    $_SESSION["discard_after"] = $now + $sessiondiscard_timer;

    if ($admin_required) {
        if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
            header("location:area_utenti.php?action=login_page");
            exit();
        }
    }
    if ($login_required) {
        if (!isset($_SESSION["a"])) {
            header("location:area_utenti.php?action=login_page");
            exit();
        }
    }
}
$menuvoices = [
    "Home" => "home.php",
    "Contatti" => "contatti.php",
    "Programmazione" => "programmazione.php",
    "Info e Costi" => "info.php",
];
function GeneratePage(
    $page,
    $content,
    $breadcrumbs,
    $title,
    $description,
    $keywords,
    $jshead,
    $jsbody
) {
    global $site_template;
    global $menuvoices;
    $output = $site_template;
    $output = str_replace("<PAGETITLE/>", $title, $output);
    $output = str_replace("<KEYWORDS/>", $keywords, $output);
    $output = str_replace("<DESCRIPTION/>", $description, $output);
    $output = str_replace("<JAVASCRIPT-HEAD/>", $jshead, $output);
    $menu = '<ul id="menu" class="closedMenu">';
    foreach ($menuvoices as $name => $link) {
        if ($name != $page) {
            if($name == "Home"){
                $menu = $menu . "<li><a href=\"" . $link . "\"> <span lang=\"en\">" . $name . "</span></a></li>";
            }else{
                $menu = $menu . "<li><a href=\"" . $link . "\">" . $name . "</a></li>";
            }
        } else {
            if($name == "Home"){
                $menu = $menu . "<li class=\"menu_name\"><span lang=\"en\">" . $name . "</span></li>";
            }else{
                $menu = $menu . "<li class=\"menu_name\">" . $name . "</li>";
            }
        }
    }

    if ($page == "login" && !isset($_SESSION["a"])) {
        $menu = $menu . "<li id=\"loginMenu\" class=\"menu_name\">Area Utenti</li>";
    } elseif ($page == "login" && isset($_SESSION["a"])) {
        $menu =
            $menu .
            "<li id=\"loginMenu\" class=\"menu_name\">" .
            $_SESSION["a"] .
            "</li>";
    } elseif ($page != "login" && isset($_SESSION["a"])) {
        $menu = $menu . "<li id=\"loginMenu\">";
        $menu =
            $menu .
            '<a id="loginbutton" href="./area_utenti.php?action=getProfile">' .
            $_SESSION["a"] .
            "</a>";
        $menu = $menu . "</li>";
    } else {
        $menu = $menu . "<li id=\"loginMenu\">";
        $menu =
            $menu .
            '<a id="loginbutton" href="./area_utenti.php?action=login_page">Area Utenti</a>';
        $menu = $menu . "</li>";
    }
    $menu = $menu . "</ul>";

    $output = str_replace("<MENU/>", $menu, $output);
    $admin = "";
    if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
        if($page!="admin"){
            $admin = "<ul><li><a href='amministrazione.php'>Amministrazione</a></li></ul>";
        }else{
            $admin = "<ul><li class=\"menu_name\">Amministrazione</li></ul>";
        }
    }
    $output = str_replace("<ADMIN/>", $admin, $output);
    $output = str_replace("<BREADCRUMB/>", $breadcrumbs, $output);
    $output = str_replace("<CONTENT/>", $content, $output);
    $output = str_replace("<JAVASCRIPT-BODY/>", $jsbody, $output);
    return $output;
}
?>
