<?php

require_once "SingletonDB.php";

$IDfilm = $_POST["IDfilm"];

$db = SingletonDB::getInstance();

$preparedQuery = $db
    ->getConnection()
    ->prepare(
        "SELECT * FROM Film INNER JOIN Proiezione ON (Film.ID = Proiezione.IDFilm) WHERE Film.ID = ?"
    );
$preparedQuery->bind_param("i", $IDfilm);
$preparedQuery->execute();

$result = $preparedQuery->get_result();

$db->disconnect();

$dates = "";

while ($row = $result->fetch_assoc()) {
    $dates =
        $dates .
        '<option value="' .
        $row["Data"] .
        '">' .
        $row["Data"] .
        "</option>";
}

echo json_encode(["datesHTML" => $dates]);

?>
