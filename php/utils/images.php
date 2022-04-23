<?php
session_start();
$now = time();
    if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['discard_after'] = $now+200;
if(!isset($_SESSION['admin'])||!$_SESSION['admin']){
    echo '{"status":"unauthorized"}';
    exit();
}
$reply=new \stdClass();
$reply->status="none";
$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check) {
    $reply->status="ok";
    } else {
    $reply->status="formato non valido";
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    $reply->status="file gia presente";
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $reply->status="dimensione file eccessiva";
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $reply->status="formato non valido";  
}
if ($reply->status=="ok") {

    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $reply->status=="errore server"
    } 
}
?>
