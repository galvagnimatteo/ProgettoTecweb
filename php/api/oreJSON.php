<?php

include "../utils/SingletonDB.php";

$IDfilm = $_POST["IDfilm"];
$Date = $_POST["Date"];

$db = SingletonDB::getInstance();

$preparedQuery = $db
    ->getConnection()
    ->prepare(
        "SELECT * FROM Film INNER JOIN Proiezione ON (Film.ID = Proiezione.IDFilm) WHERE Film.ID = ? AND Proiezione.Data = ?"
    );
$preparedQuery->bind_param("is", $IDfilm, $Date);
$preparedQuery->execute();

$result = $preparedQuery->get_result();

$db->disconnect();

$hours = "";

while ($row = $result->fetch_assoc()) {
    $hours =
        $hours .
        '<option value="' .
        $row["Orario"] .
        '">' .
        $row["Orario"] .
        "</option>"; //(id proiezione)
}

echo json_encode(["hoursHTML" => $hours]);

?>
