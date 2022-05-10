<?php

function registerControls(
    $username,
    $name,
    $surname,
    $email,
    $password,
    $confirm_password
) {
    $usernameRegex = "/^[a-zA-Z0-9]+$/";

    if (!preg_match($usernameRegex, $username)) {
        return "L'username deve contenere solo lettere e numeri.";
    }

    $nameRegrex = "/^[a-zA-Z]+$/";

    if (!preg_match($nameRegrex, $name)) {
        return "Il nome può essere composto da sole lettere.";
    }

    if (!preg_match($nameRegrex, $surname)) {
        return "Il cognome può essere composto da sole lettere.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return "L'email inserita non è valida, deve essere nella forma example@email.com";
    }

    if ($password != null) {
        if (strlen($password) < 8 || str_contains($password, " ")) {
            return "La password deve essere di almeno 8 caratteri e non può contenere spazi.";
        }

        if ($password != $confirm_password) {
            return "La conferma password è diversa dalla password inserita.";
        }
    }

    return "OK";
}

function loginControls($username, $password) {

    if ($username == "admin" && ($password = "admin")) {
        return "OK";
    }

    if ($username == "user" && ($password = "user")) {
        return "OK";
    }

    if(strlen($password) < 8 || str_contains($password, " ")){

        return "La password deve essere di almeno 8 caratteri e non può contenere spazi.";

    }

    return "OK";
}
function CheckFilm(
        $Titolo,
        $Genere,
        $DataUscita,
        $Descrizione,
        $SrcImg,            
        $Durata,
        $CarouselImg,
        $Attori,
        $Regista
) {
    $titoloRegex = "/^[a-zA-Z0-9]/";

    if (!preg_match($titoloRegex, $Titolo)) {
        return "il titolo deve contenere solo lettere e numeri.";
    }

    $genereRegrex = "/^[a-zA-Z]/";

    if (!preg_match($genereRegrex, $Genere)) {
        return "Il genere può essere composto da sole lettere.";
    }

    $imageregex="/[^?#]*\.(gif|jpe?g|tiff?|png|webp|bmp)$/";
    if (!preg_match($imageregex, $SrcImg)) {
        return "immagini devono esser file validi";
    }
    if (!preg_match($imageregex, $CarouselImg)) {
        return "immagini devono esser file validi";
    }
    if($Durata<=0){
        return "la durata deve essere maggiore di 0";
    }
    return "OK";
}
function CheckProiezione(
                $connection,
                $Data,
                $IDFilm,
                $NumeroSala,
                $orario
            )
{
    if($NumeroSala<0||$NumeroSala>3){
        return "sala insistente";
    }
    $query='SELECT Durata FROM Film where ID=?;';
    $preparedQuery = $connection->prepare($query);
    $preparedQuery->bind_param(
        's',
        $IDFilm
    );
    $preparedQuery->execute();
    $res=$preparedQuery->get_result();
    $row=$res->fetch_array(MYSQLI_NUM);
    if($row){
        $duratafilm=$row[0]+15;//15 minuti di margine per la pulizia della sala;
    }
    else{
        return "film non esistente";
    }   
    $query='SELECT count(*) FROM Proiezione,Film WHERE Film.ID=Proiezione.IDFilm '.
    'AND Data= ? './/stesso Giorno
    'AND NumeroSala= ? AND'.//stessa sala
    '(( MINUTE(TIMEDIFF( ? ,Orario))>0 AND MINUTE(TIMEDIFF( ? ,Orario))<(Durata+15))'.//film inserito è tra inizio e fine di film gia presente
    'OR (MINUTE(TIMEDIFF(Orario, ? ))>0 AND MINUTE(TIMEDIFF(Orario, ? ))< ? ));';//film presente è tra inizio e fine di film inserito
    $preparedQuery = $connection->prepare($query);
    $preparedQuery->bind_param(
        'ssssssi',
        $Data,
        $NumeroSala,
        $orario,
        $orario,
        $orario,
        $orario,
        $duratafilm
    );
    $preparedQuery->execute();
    $res=$preparedQuery->get_result();
    if($res->fetch_array(MYSQLI_NUM)[0]>0)
    {
        return "sovrapposizione con un altro Film";
    }
    return "OK";
}


function pulisci(&$value)
{
    // elimina gli spazi
    $value = trim($value);
    // converte i caratteri speciali in entità html (ex. &lt;)
    $value = htmlentities($value);
    // rimuove tag html, non li vogliamo
    $value = strip_tags($value);
	//return $value;
}
function return_cleaned($value)
{
    pulisci($value);
    return $value;
}

?>
