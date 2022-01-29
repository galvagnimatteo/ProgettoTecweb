<?php

session_start();
$now = time();
if (isset($_SESSION["discard_after"]) && $now > $_SESSION["discard_after"]) {
    session_unset();
    session_destroy();
    session_start();
}

$_SESSION["discard_after"] = $now + 30;

$document = file_get_contents("../html/template.html"); //load template

$content = "";
if(isset($_POST["film"]) &&
    isset($_POST["sala"]) &&
    isset($_POST["Giorno"]) 
    )
{
    $db = SingletonDB::getInstance();
            $query =
                "SELECT username FROM Amministratori WHERE  username=? AND password=?";
            $preparedQuery = $db->getConnection()->prepare($query);
            $preparedQuery->bind_param("ss", $email, $password);

            $email = $_POST["username"];
            $password = $_POST["password"];

            $preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();

            $db->disconnect();
            $preparedQuery->close();

            if ($resultCast->num_rows > 0) {
                $row = $resultCast->fetch_assoc();
                $_SESSION["a"] = $row["username"];
                $_SESSION["admin"]=true
                header("location:admin.php");
            } else {
                unset($_SESSION["a"]);
                $content = file_get_contents("../html/login_admin_content.html");
            $content = $content=str_replace("<STATUS>", "<p class='faliure'>login non valido riprovare</p>", $content);
    }
}
else{
    $content = file_get_contents("../html/login_admin_content.html");
    $content = $content=str_replace("<STATUS>", "", $content);
}
$document = str_replace(
    "<BREADCRUMB>",
    '<a href="admin.php">amministrazione</a> / login',
    $document
);
$document = str_replace("/php/admin_login.php", "#", $document);

$document = str_replace("<CONTENT>", $content, $document);
echo $document;

?>
