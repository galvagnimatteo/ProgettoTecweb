<?php
session_start();
require_once "utils/generaPagina.php";
require_once "utils/SingletonDB.php";
//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta
$info_content = file_get_contents("../html/info.html"); //load content

$title = "Info e Costi - PNG Cinema";
$keywords = "info, costi, costo biglietto, convenzioni, sconti";
$description =
    "Pagina informativa sui costi: è possibile consultare prezzi e convenzioni sui biglietti.";
$breadcrumbs = '<a href="home.php">Home</a> / Info e Costi';

$db = SingletonDB::getInstance();
$result = $db
    ->getConnection()
    ->query("SELECT * FROM Prezzi");
$db->disconnect();

$prezzi = array();

if (!empty($result) && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
		$p_int = str_replace(".", ",", $row["PrezzoRidotto"]);
		$p_rid = str_replace(".", ",", $row["PrezzoIntero"]);
		
		switch($row["Giorno"]) {
			case "Domenica":
				//considero festivi
				$prezzi["festivi-int"] = $p_int;
				$prezzi["festivi-rid"] = $p_rid;
				break;
			
			case "Mercoledì":
				$prezzi["mercoledi-int"] = $p_int;
				$prezzi["mercoledi-rid"] = $p_rid;
				break;
			
			case "Lunedì":
				//considero feriali
				$prezzi["feriali-int"] = $p_int;
				$prezzi["feriali-rid"] = $p_rid;
				break;
			
		}
	}

} else {
	header("Location: 500.php");
    die();
}

$info_content = str_replace(
		"<FER-INT>",
		$prezzi["feriali-int"],
		$info_content
	);
$info_content = str_replace(
	"<FER-RID>",
	$prezzi["feriali-rid"],
	$info_content
);
$info_content = str_replace(
	"<FEST-INT>",
	$prezzi["festivi-int"],
	$info_content
);
$info_content = str_replace(
	"<FEST-RID>",
	$prezzi["festivi-rid"],
	$info_content
);
$info_content = str_replace(
	"<MER-INT>",
	$prezzi["mercoledi-int"],
	$info_content
);
$info_content = str_replace(
	"<MER-RID>",
	$prezzi["mercoledi-rid"],
	$info_content
);

//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "Info e Costi",
    $info_content,
    $breadcrumbs,
    $title,
    $description,
    $keywords,
    "",
    ""
);
?>
