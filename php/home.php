<?php
session_start();
require_once "utils/generaPagina.php";
require_once "utils/SingletonDB.php";
//CheckSession($login_required, $admin_required);
CheckSession(false, false); //refresh della sessione se scaduta

$home_content = file_get_contents("../html/home.html");
$quickpurchase_films = "";
$cards = "";

//Query per inserimento film (anche acquisto rapido)------------------------

$db = SingletonDB::getInstance();
$resultFilms = $db
    ->getConnection()
    ->query("SELECT *, Film.ID AS FilmID FROM Film INNER JOIN Proiezione ON Film.ID=Proiezione.IDFilm WHERE Data > current_date GROUP BY Film.ID ORDER BY DataUscita DESC");
$db->disconnect();

//--------------------------------------------------------------------------

if (!empty($resultFilms) && $resultFilms->num_rows > 0) {
    $card_home_template = file_get_contents("../html/items/card_home.html");
    $carouselFilms = 0;
    $carouselImages = "";

    while ($row = $resultFilms->fetch_assoc()) {
        if ($carouselFilms < 3) {
            if ($carouselFilms == 0) {
                $carouselImages =
                    '<li class="slide not-hidden">
                    <img src="../images/' .
                    $row["CarouselImg"] .
                    '" alt="' .
                    "Locandina " .
                    $row["Titolo"] .
                    '"/>
                </li>';
            } else {
                $carouselImages =
                    $carouselImages .
                    '<li class="slide">
                    <img src="../images/' .
                    $row["CarouselImg"] .
                    '" alt="' .
                    "Locandina " .
                    $row["Titolo"] .
                    '"/>
                </li>';
            }
        }

        $carouselFilms = $carouselFilms + 1;

        $quickpurchase_films =
            $quickpurchase_films .
            '<option value="' .
            $row["FilmID"] .
            '">' .
            $row["Titolo"] .
            "</option>";

        $card_home_item = $card_home_template;

        $card_home_item = str_replace(
            "<FILMTITLE>",
            $row["Titolo"],
            $card_home_item
        );

        $description = $row["Descrizione"];
        $description = substr($description, 0, 200);
        $description = $description . "...";
        $card_home_item = str_replace(
            "<FILMDESCRIPTION>",
            $description,
            $card_home_item
        );

        $card_home_item = str_replace(
            "<LINK>",
            "schedafilm.php?idfilm=" . $row["FilmID"],
            $card_home_item
        );

        $card_home_item = str_replace(
            "<SRCIMG>",
            "../images/" . $row["SrcImg"],
            $card_home_item
        );

        $card_home_item = str_replace(
            "<ALTIMG>",
            "Locandina" . $row["Titolo"],
            $card_home_item
        );

        $cards = $cards . $card_home_item;
    }

    $home_content = str_replace(
        "<CAROUSELIMAGES>",
        $carouselImages,
        $home_content
    );
} else {
    $cards = '<p class="error">Nessun film trovato.</p>
              <p class="errorDescription"> Nessun film in programmazione nelle prossime settimane. </p>';
}
$home_content = str_replace(
    "<FILM-OPTIONS>",
    $quickpurchase_films,
    $home_content
);
$home_content = str_replace("<CARDS-HOME>", $cards, $home_content);

if(isset($_GET["error"])){
    if($_GET["error"] == "emptyData"){
        $home_content = str_replace(
            '<p id="quickpurchaseError" class="error hidden"> </p>',
            '<p id="quickpurchaseError" class="error"> Riempi prima tutti i campi per acquistare un biglietto. </p>',
            $home_content);
    }
}

$title = "Home - PNG Cinema";
$keywords = "ultime uscite, acquisto, acquisto rapido";
$description =
    "Pagina principale: è possibile consultare le ultime uscite in programmazione e acquistare rapidamente un biglietto.";
$breadcrumbs = "Home / ";
$jshead = '';
$jsbody = '<script type="text/javascript" src="../js/carousel.js"> </script>
                <script type="text/javascript" src="../js/jquery-3.6.0.min.js"> </script>
                <script type="text/javascript" src="../js/quickpurchase.js"> </script>';
//GeneratePage($page,$content,$breadcrumbs,$title,$description,$keywords,$jshead,$jsbody);
echo GeneratePage(
    "Home",
    $home_content,
    $breadcrumbs,
    $title,
    $description,
    $keywords,
    $jshead,
    $jsbody
);
?>
