<?php
function filtraTestoInglese($testo){
    $testo = str_replace(
        "{",
        '<span lang="en">',
        $testo
    );
    $testo = str_replace(
        "}",
        '</span>',
        $testo
    );

    return $testo;
}
?>