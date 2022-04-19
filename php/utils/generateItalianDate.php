<?php

function generateItalianDate($data)
{
    //funziona con il formato anno-mese-giorno
    $timestamp = date_timestamp_get(date_create($data));
    $mesi = [
        1 => "gennaio",
        "febbraio",
        "marzo",
        "aprile",
        "maggio",
        "giugno",
        "luglio",
        "agosto",
        "settembre",
        "ottobre",
        "novembre",
        "dicembre",
    ];

    $giorni = [
        "Sunday" => "Domenica",
        "Monday" => "Lunedì",
        "Tuesday" => "Martedì",
        "Wednesday" => "Mercoledì",
        "Thursday" => "Giovedì",
        "Friday" => "Venerdì",
        "Saturday" => "Sabato",
    ];

    list($nomeGiorno, $giorno, $mese, $anno) = explode(
        "-",
        date("l-d-m-Y", $timestamp)
    );
    return $giorni[$nomeGiorno] .
        " " .
        $giorno .
        " " .
        $mesi[intval($mese)] .
        " " .
        $anno;
}

?>
