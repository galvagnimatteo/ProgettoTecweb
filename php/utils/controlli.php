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

    $imageregex="/^https?:\/\/(?:[a-z0-9\-]+\.)+[a-z]{2,6}(?:\/[^\/#?]+)+\.(?:jpe?g|gif|png|bmp)/";
    if (!preg_match($imageregex, $SrcImg)) {
        return "immagini devono esser url validi";
    }
    if (!preg_match($imageregex, $CarouselImg)) {
        return "immagini devono esser url validi";
    }
    if($Durata<=0){
        return "la durata deve essere maggiore di 0";
    }
    return "OK";
}
function CheckProiezione(
                $Data,
                $IDFilm,
                $NumeroSala,
                $orario
            )
{
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
