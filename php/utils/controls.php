<?php

function registerControls($username, $name, $surname, $email, $password, $confirm_password){

    $usernameRegex = "/^[a-zA-Z0-9]+$/";

    if(!preg_match($usernameRegex, $username)){

        return "L'username deve contenere solo lettere e numeri.";

    }

    $nameRegrex = "/^[a-zA-Z]+$/";

    if(!preg_match($nameRegrex, $name)){

        return "Il nome può essere composto da sole lettere.";

    }

    if(!preg_match($nameRegrex, $surname)){

        return "Il cognome può essere composto da sole lettere.";

    }

    if(!str_contains($email, "@")){

        return "Chiocciola (@) mancante nell'email.";

    }

    if(strlen($password) < 8 || str_contains($password, " ")){

        return "La password deve essere di almeno 8 caratteri e non può contenere spazi.";

    }

    if($password != $confirm_password){

        return "La conferma password è diversa dalla password inserita.";

    }

    return "OK";

}

function loginControls($username, $password){

    /*if(!str_contains($email, "@")){

        return "Chiocciola (@) mancante nell'email.";

    }*/

    if(strlen($password) < 8 || str_contains($password, " ")){

        return "La password deve essere di almeno 8 caratteri e non può contenere spazi.";

    }

    return "OK";

}

?>